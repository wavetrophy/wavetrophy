<?php

namespace App\Controller;


use App\Entity\User;
use App\Service\Firebase\DatabaseService;
use App\Service\Firebase\TopicService;
use App\Service\Group\GroupService;
use App\Service\Question\QuestionService;
use App\Service\User\UserService;
use App\Service\Wave\WaveService;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use Moment\Moment;
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
     * AdminController constructor.
     *
     * @param WaveService $wave
     * @param UserService $user
     * @param QuestionService $question
     * @param GroupService $group
     * @param TopicService $topic
     * @param TokenStorageInterface $storage
     * @param DatabaseService $database
     */
    public function __construct(
        WaveService $wave,
        UserService $user,
        QuestionService $question,
        GroupService $group,
        TopicService $topic,
        TokenStorageInterface $storage,
        DatabaseService $database
    ) {
        $this->wave = $wave;
        $this->user = $user;
        $this->question = $question;
        $this->group = $group;
        $this->topic = $topic;
        $this->storage = $storage;
        $this->firebaseDB = $database;
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
}
