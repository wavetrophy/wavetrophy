<?php

namespace App\Listeners;

use App\Entity\Media;
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
     * @param OnFlushEventArgs $args
     */
    public function onFlush(OnFlushEventArgs $args)
    {
        $em = $args->getEntityManager();
        $entities = $em->getUnitOfWork()->getScheduledEntityInsertions();

        foreach ($entities as $entity) {
            if ($entity instanceof Media) {
                $this->handleMedia($entity, $em);
            }
        }
    }

    /**
     * @param Media $entity
     * @param \Doctrine\ORM\EntityManager $em
     */
    private function handleMedia(Media $entity, \Doctrine\ORM\EntityManager $em): void
    {
        $fileName = $entity->getFile()->getRealPath();
        $url = str_replace($this->uploadDir, $this->uriPrefix, $fileName);
        $path = dirname($url);
        $entity->setPath($path);
        $entity->setUrl($url);
        $em->persist($entity);
    }
}
