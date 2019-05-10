<?php

namespace App\Controller;

use App\Entity\Team;
use App\Entity\Traits\MetaFieldTrait;
use App\Entity\User;
use App\Service\Firebase\DatabaseService;
use App\Service\Firebase\TopicService;
use App\Service\Group\GroupService;
use App\Service\Question\QuestionService;
use App\Service\User\UserService;
use App\Service\Wave\WaveService;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use Moment\Moment;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class AdminController
 */
class AdminController extends EasyAdminController
{
    /**
     * @var WaveService
     */
    private $wave;

    /**
     * @var UserService
     */
    private $user;

    /**
     * @var QuestionService
     */
    private $question;

    /**
     * @var GroupService
     */
    private $group;

    /**
     * @var TopicService
     */
    private $topic;

    /**
     * @var TokenStorageInterface
     */
    private $storage;

    /**
     * @var DatabaseService
     */
    private $firebaseDB;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * AdminController constructor.
     *
     * @param WaveService $wave
     * @param UserService $user
     * @param QuestionService $question
     * @param GroupService $group
     * @param TopicService $topic
     * @param TokenStorageInterface $storage
     * @param DatabaseService $database
     * @param LoggerInterface $logger
     */
    public function __construct(
        WaveService $wave,
        UserService $user,
        QuestionService $question,
        GroupService $group,
        TopicService $topic,
        TokenStorageInterface $storage,
        DatabaseService $database,
        LoggerInterface $logger
    ) {
        $this->wave = $wave;
        $this->user = $user;
        $this->question = $question;
        $this->group = $group;
        $this->topic = $topic;
        $this->storage = $storage;
        $this->firebaseDB = $database;
        $this->logger = $logger;
    }

    /**
     * @Route("/", name="easyadmin")
     * @Route("/", name="admin")
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $this->initialize($request);

        if (null === $request->query->get('entity')) {
            $waveId = $this->wave->getCurrentWaveId();
            $groups = $this->group->getGroupsForWave($waveId);
            $questions = $this->question->getQuestionsForWave($waveId);
            $users = $this->user->getWaveParticipants($waveId);

            $topics = $this->topic->getTopics($waveId, $groups, $questions);

            $now = time();
            $day = 24 * 60 * 60;
            $dates = [];

            /** @var User $user */
            $user = $this->storage->getToken()->getUser();
            $locale = $user->getLocale();
            Moment::setLocale($locale, true);

            for ($i = 2; $i <= 7; $i++) {
                $time = $now + (($i + 1) * $day);
                $dates[] = [
                    'value' => $i,
                    'day' => (new Moment($time))->format('l'),
                ];
            }

            return $this->render(
                'admin/dashboard.html.twig',
                ['topics' => $topics, 'dates' => $dates, 'users' => $users]
            );
        }

        return parent::indexAction($request);
    }

    /**
     * Persist entity hook
     *
     * @param Team $team
     */
    protected function persistTeamEntity(Team $team)
    {
        $em = $this->em;
        $team->getUsers()->forAll(function ($key, User $user) use ($team, $em) {
            $user->setTeam($team);
            $em->persist($user);

            return true;
        });

        $this->persistEntity($team);
    }

    /**
     * Update entity Hook
     *
     * @param Team $team
     */
    protected function updateTeamEntity(Team $team)
    {
        $em = $this->em;
        $team->getUsers()->forAll(function ($key, User $user) use ($team, $em) {
            $user->setTeam($team);
            $em->persist($user);

            return true;
        });

        $this->persistEntity($team);
    }

    /**
     * Find all
     *
     * @param string $entityClass
     * @param int $page
     * @param int $maxPerPage
     * @param null $sortField
     * @param null $sortDirection
     * @param null $dqlFilter
     *
     * @return mixed|\Pagerfanta\Pagerfanta
     */
    protected function findAll(
        $entityClass,
        $page = 1,
        $maxPerPage = 15,
        $sortField = null,
        $sortDirection = null,
        $dqlFilter = null
    ) {
        $alias = $this->getAlias($entityClass);
        $query = $this->em->getRepository($entityClass)
            ->createQueryBuilder($alias);

        $usingTrait = in_array(
            MetaFieldTrait::class,
            array_keys((new \ReflectionClass($entityClass))->getTraits())
        );

        if ($usingTrait) {
            $query->where($alias . '.deletedAt IS NULL and ' . $alias . '.createdAt IS NOT NULL');

            // The checked aliases arent really required, but its good for debugging to have them...
            $checkedAliases = [];
            $checkedAliases[$alias] = true;

            $query = $this->getRecursiveEntityDeletedAtQuery($query, $entityClass, $alias, $checkedAliases);
            $this->logger->info(
                "Read query \n\n{query}\n\n with aliases\n{aliases}",
                ['query' => $query->getDQL(), 'aliases' => json_encode($checkedAliases, JSON_PRETTY_PRINT)]
            );
        }
        
        if (null === $sortDirection || !\in_array(\strtoupper($sortDirection), ['ASC', 'DESC'])) {
            $sortDirection = 'DESC';
        }

        $this->dispatch(EasyAdminEvents::POST_LIST_QUERY_BUILDER, [
            'query_builder' => $query,
            'sort_field' => $sortField,
            'sort_direction' => $sortDirection,
        ]);

        return $this->get('easyadmin.paginator')->createOrmPaginator($query, $page, $maxPerPage);
    }

    /**
     * Generate a query that checks recursively for deleted parent (ManyToOne) entites (Group -> checks for deleted
     * Wave)
     *
     * @param QueryBuilder $query
     * @param $entityClass
     * @param string $entityAlias
     * @param $checkedAliases
     *
     * @return QueryBuilder
     */
    private function getRecursiveEntityDeletedAtQuery(
        QueryBuilder $query,
        $entityClass,
        string $entityAlias,
        &$checkedAliases
    ) {
        $meta = $this->em->getMetadataFactory()->getMetadataFor($entityClass);
        $names = $this->getAssociationFields($meta);

        foreach ($names as $name) {
            $mapping = $meta->getAssociationMapping($name);

            if ($mapping['isOwningSide']) {
                $alias = $this->getAlias($mapping['targetEntity']);
                $join = $entityAlias . '.' . $mapping['fieldName'];

                $query->innerJoin($join,
                    $alias)->andWhere($alias . '.deletedAt IS NULL and ' . $alias . '.createdAt IS NOT NULL');

                $checkedAliases[$alias] = true;
                $this->getRecursiveEntityDeletedAtQuery($query, $mapping['targetEntity'], $alias, $checkedAliases);
            }
        }

        return $query;
    }

    /**
     * Converts the Class\Name\Of\A\Class to class_name_of_a_class
     *
     * @param string $className
     *
     * @return string
     */
    private function getAlias(string $className)
    {
        $class = str_replace('\\', '_', $className) . uniqid('___');

        return strtolower($class);
    }

    /**
     * @param ClassMetadata $meta
     *
     * @return array|string[]
     */
    private function getAssociationFields(ClassMetadata $meta)
    {
        $names = $meta->getAssociationNames();
        $map = [
            'createdBy' => true,
            'updatedBy' => true,
        ];
        $names = array_filter($names, function ($name) use ($map) {
            // remove all names that are in the map
            return !isset($map[$name]);
        });

        return $names;
    }
}
