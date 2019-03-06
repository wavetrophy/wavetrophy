<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\MetaFieldTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Answer
 *
 * @ORM\Table(name="answer", indexes={@ORM\Index(name="fk_answer_question1_idx", columns={"question_id"})})
 * @ORM\Entity
 * @ApiResource()
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 */
class Answer
{
    use MetaFieldTrait;
    protected $iHaveSetTheSoftDeletedAnnotation = true;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="answer", type="string", length=1000, nullable=false)
     * @Assert\NotBlank()
     */
    private $answer;

    /**
     * @var bool
     *
     * @ORM\Column(name="approved", type="boolean", nullable=false)
     */
    private $approved = false;

    /**
     * @var Question
     *
     * @ORM\ManyToOne(targetEntity="Question",inversedBy="answers")
     * @ORM\JoinColumn(name="question_id", referencedColumnName="id")
     */
    private $question;

    /**
     * Answer constructor.
     *
     * @param string|null $answer
     * @param bool|null $approved
     * @param Question|null $question
     */
    public function __construct(
        ?string $answer,
        ?bool $approved,
        ?Question $question
    ) {
        $this->answer = $answer;
        $this->approved = $approved;
        $this->question = $question;
    }

    public function __toString(): ?string
    {
        return $this->answer;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAnswer(): ?string
    {
        return $this->answer;
    }

    public function setAnswer(?string $answer): self
    {
        $this->answer = $answer;

        return $this;
    }

    public function getApproved(): ?bool
    {
        return $this->approved;
    }

    public function setApproved(?bool $approved): self
    {
        $this->approved = $approved;

        return $this;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): self
    {
        $this->question = $question;

        return $this;
    }
}
