<?php

namespace App\Service\Firebase;

use App\Entity\Group;
use App\Entity\Question;

/**
 * Class TopicService
 */
class TopicService
{
    /**
     * @param int $waveId
     * @param Group[] $groups
     * @param Question[] $questions
     *
     * @return array
     */
    public function getTopics(?int $waveId, ?array $groups, ?array $questions)
    {
        $topics = [
            'wave' => ['name' => 'current WAVE', 'key' => 'wave-' . $waveId],
            'list' => [],
        ];

        foreach ($groups as $group) {
            $topics['list'][] = [
                'type' => 'group',
                'name' => $group->getName(),
                'key' => 'group-' . $group->getId(),
            ];
        }

        foreach ($questions as $question) {
            $topics['list'][] = [
                'type' => 'question',
                'name' => 'Question: ' . $question->getTitle(),
                'key' => 'question-' . $question->getId(),
            ];
        }

        return $topics;
    }
}
