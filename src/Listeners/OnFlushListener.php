<?php

namespace App\Listeners;

use App\Entity\Answer;
use App\Entity\Location;
use App\Entity\Media;
use App\Entity\User;
use App\Entity\UserEmail;
use App\Entity\UserPhonenumber;
use App\Exception\ValidationException;
use App\Service\Firebase\NotificationService;
use App\Service\Image\SVGProfilePicture;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Symfony\Component\HttpFoundation\File\File;

/**
 * Class MediaPreFlushListener
 */
class OnFlushListener
{
    /**
     * @var
     */
    private $uriPrefix;
    /**
     * @var
     */
    private $uploadDir;
    /**
     * @var SVGProfilePicture
     */
    private $profilePictureGenerator;
    /**
     * @var NotificationService
     */
    private $notifications;

    /**
     * OnFlushListener constructor.
     *
     * @param $data
     * @param SVGProfilePicture $profilePictureGenerator
     * @param NotificationService $notifications
     */
    public function __construct(
        $data,
        SVGProfilePicture $profilePictureGenerator,
        NotificationService $notifications
    ) {
        $this->uriPrefix = $data['media']['uri_prefix'];
        $this->uploadDir = $data['media']['upload_destination'];
        $this->profilePictureGenerator = $profilePictureGenerator;
        $this->notifications = $notifications;
    }

    /**
     * Listener
     *
     * @param OnFlushEventArgs $args
     */
    public function onFlush(OnFlushEventArgs $args)
    {
        $em = $args->getEntityManager();

        $updateEntities = $em->getUnitOfWork()->getScheduledEntityUpdates();
        $this->iterate($updateEntities, $em, 'UPDATE');

        $insertEntities = $em->getUnitOfWork()->getScheduledEntityInsertions();
        $this->iterate($insertEntities, $em, 'INSERT');


        $updateCollections = $em->getUnitOfWork()->getScheduledCollectionUpdates();
        /** @var Collection $collection */
        foreach ($updateCollections as $collection) {
            $this->iterate($collection->getValues(), $em, 'BULK_UPDATE');
        }
    }

    /**
     * Iterate through a collection
     *
     * @param array $entities
     * @param EntityManager $em
     * @param $method
     */
    private function iterate(array $entities, EntityManager $em, $method)
    {
        foreach ($entities as $entity) {
            if ($entity instanceof Media) {
                $this->handleMedia($entity, $em);
            }
            if ($entity instanceof User) {
                $this->handleUser($entity, $em);
            }
            if ($entity instanceof UserEmail || $entity instanceof UserPhonenumber) {
                $user = $entity->getUser();
                $this->handleUser($user, $em);
            }
            if ($entity instanceof Answer) {
                $this->handleAnswer($entity, $em, $method);
            }
            if ($entity instanceof Location) {

            }
        }
    }

    /**
     * Handle a Media entity.
     *
     * @param Media $entity
     * @param EntityManager $em
     */
    private function handleMedia(Media $entity, EntityManager $em): void
    {
        $entity = $this->updateMedia($entity);
        $this->persist($entity, $em);
    }

    /**
     * Handle User entity
     *
     * @param User $user
     * @param EntityManager $em
     */
    private function handleUser(User $user, EntityManager $em)
    {
        $emails = $user->getEmails();
        $email = $emails->first();
        if (!empty($email) && $user->getEmail() !== $email->getEmail()) {
            $user->setEmail($email->getEmail());
            $this->persist($user, $em);
        }
        if (empty($user->getProfilePicture())) {
            $path = $this->profilePictureGenerator->generate();

            $media = new Media();
            $media->setFile(new File($path));
            $media->setName(basename($path));
            $media = $this->updateMedia($media);
            $em->persist($media);
            $em->getUnitOfWork()->computeChangeSets();
            $user->setProfilePicture($media);
            $this->persist($user, $em);
        }
    }

    /**
     * Handle answer
     *
     * @param Answer $answer
     * @param EntityManager $em
     * @param string $method
     */
    private function handleAnswer(Answer $answer, EntityManager $em, string $method)
    {
        $questionId = $answer->getQuestion()->getId();
        $topic = "question-{$questionId}";
        switch ($method) {
            case 'INSERT':
                $message = "{$answer->getCreatedBy()->getUsername()} answered \"{$answer->getQuestion()->getTitle()}\"";
                $this->notifications->toTopic(
                    $topic,
                    $message,
                    ['open' => '/question/' . $questionId, 'answer' => $answer->getAnswer()]
                );
                break;
            case 'UPDATE':
                $uow = $em->getUnitOfWork();
                $uow->computeChangeSets();
                $changeset = $uow->getEntityChangeSet($answer);
                $approvedIsChangedToTrue = false;
                if (isset($changeset['approved'])) {
                    $approvedIsChangedToTrue = $changeset['approved'][0] === false && $changeset['approved'][1] === true;
                }
                // Approved is true and is not changed from false to true
                if ($answer->getApproved() === true && $approvedIsChangedToTrue === false) {
                    throw new ValidationException('You can not edit an approved answer');
                }
                break;
        }
    }

    /**
     * Handle location
     *
     * @param Location $location
     * @param EntityManager $em
     */
    public function handleLocation(Location $location, EntityManager $em)
    {
        $url = $location->getThumbnailimage();
        $media = $em->getRepository(Media::class)->findByUrl($url);
        if (!empty($media)) {
            $location->setThumbnail($media);
            $this->persist($location, $em);
        }
    }

    /**
     * Handle user entity.
     *
     * @param $entity
     * @param EntityManager $em
     */
    private function persist($entity, EntityManager $em)
    {
        $em->persist($entity);
        $meta = $em->getClassMetadata(get_class($entity));
        $em->getUnitOfWork()->recomputeSingleEntityChangeSet($meta, $entity);
    }

    /**
     * @param Media $entity
     *
     * @return Media
     */
    private function updateMedia(Media $entity): Media
    {
        $fileName = $entity->getFile()->getRealPath();
        $url = str_replace($this->uploadDir, $this->uriPrefix, $fileName);
        $path = dirname($url);
        $entity->setPath($path);
        $entity->setUrl($url);
        return $entity;
    }
}
