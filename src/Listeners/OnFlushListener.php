<?php

namespace App\Listeners;

use App\Entity\Answer;
use App\Entity\Hotel;
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
    private $mediaUriPrefix;
    /**
     * @var
     */
    private $mediaUploadDir;
    /**
     * @var SVGProfilePicture
     */
    private $profilePictureGenerator;
    /**
     * @var NotificationService
     */
    private $notifications;
    private $hotelUriPrefix;
    private $hotelUploadDir;

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
        $this->mediaUriPrefix = $data['media']['uri_prefix'];
        $this->mediaUploadDir = $data['media']['upload_destination'];
        $this->hotelUriPrefix = $data['hotel_thumbnail']['uri_prefix'];
        $this->hotelUploadDir = $data['hotel_thumbnail']['upload_destination'];
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
                if (!empty($user)) {
                    $this->handleUser($user, $em);
                }
            }
            if ($entity instanceof Answer) {
                $this->handleAnswer($entity, $em, $method);
            }
            if ($entity instanceof Hotel) {
                $this->handleHotel($entity, $em);
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
        if (!empty($emails)) {
            $email = $emails->first();
            if (!empty($email) && $user->getEmail() !== $email->getEmail()) {
                $user->setEmail($email->getEmail());
                $this->persist($user, $em);
            }
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

    public function handleHotel(Hotel $entity, EntityManager $em)
    {
        if ($entity->getThumbnailImage()) {
            $fileName = $entity->getThumbnailImage()->getRealPath();
            $url = str_replace($this->hotelUploadDir, $this->hotelUriPrefix, $fileName);
            $path = dirname($url);
            $entity->setThumbnailUrl($path);
            $this->persist($entity, $em);
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
        $url = str_replace($this->mediaUploadDir, $this->mediaUriPrefix, $fileName);
        $path = dirname($url);
        $entity->setPath($path);
        $entity->setUrl($url);
        return $entity;
    }
}
