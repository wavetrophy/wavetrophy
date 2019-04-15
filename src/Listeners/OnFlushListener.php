<?php

namespace App\Listeners;

use App\Entity\Media;
use App\Entity\User;
use App\Entity\UserEmail;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Event\OnFlushEventArgs;

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
     * OnFlushListener constructor.
     *
     * @param $data
     */
    public function __construct($data)
    {
        $this->uriPrefix = $data['media']['uri_prefix'];
        $this->uploadDir = $data['media']['upload_destination'];
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
            if ($entity instanceof UserEmail) {
                $user = $entity->getUser();
                $this->handleUser($user, $em);
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
        $fileName = $entity->getFile()->getRealPath();
        $url = str_replace($this->uploadDir, $this->uriPrefix, $fileName);
        $path = dirname($url);
        $entity->setPath($path);
        $entity->setUrl($url);
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
        if (!empty($email)) {
            $user->setEmail($email->getEmail());
        }
        $this->persist($user, $em);
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
}
