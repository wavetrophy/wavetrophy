<?php

namespace App\Service\Question;

use App\Entity\Question;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class QuestionService
 */
class QuestionService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * QuestionService constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param int $waveId
     *
     * @return Question[]
     */
    public function getQuestionsForWave(int $waveId): array
    {
        return $this->em->getRepository(Question::class)
            ->getQuestionsForWave($waveId);
    }
}
