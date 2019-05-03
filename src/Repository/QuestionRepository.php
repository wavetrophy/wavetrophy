<?php

namespace App\Repository;

use App\Entity\Question;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Class QuestionRepository
 */
class QuestionRepository extends ServiceEntityRepository
{
    /**
     * QuestionRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Question::class);
    }

    /**
     * @param int $waveId
     *
     * @return Question[]
     */
    public function getQuestionsForWave(int $waveId): array
    {
        $query = $this->createQueryBuilder('q');
        $query->innerJoin('q.group', 'g')
            ->innerJoin('g.wave', 'w')
            ->andWhere('w.id = :waveId')
            ->setParameter('waveId', $waveId);
        $result = $query->getQuery()->getResult();
        return $result;
    }
}
