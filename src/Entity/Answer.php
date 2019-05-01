<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\MetaFieldTrait;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Answer
 *
 * @ORM\Table(name="answer", indexes={@ORM\Index(name="fk_answer_question1_idx", columns={"question_id"})})
 * @ORM\Entity
 * @ApiResource(
 *     normalizationContext={
 *         "groups"={"answer:read"},
 *         "enable_max_depth"=true,
 *     },
 *     denormalizationContext={
 *         "groups"={"answer:edit"}
 *     },
 *     itemOperations={
 *         "get",
 *         "put"={"access_control"="user.getId() == object.getCreatorId()"},
 *         "delete"={"access_control"="user.getId() == object.getCreatorId() and object.getApproved() !== true"},
 *     },
 *     collectionOperations={"get", "post"},
 * )
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
     * @Groups({"answer:read", "question:read"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="answer", type="string", length=1000, nullable=false)
     * @Assert\NotBlank()
     * @Groups({"answer:read", "question:read", "answer:edit"})
     */
    private $answer;

    /**
     * @var bool
     *
     * @ORM\Column(name="approved", type="boolean", nullable=false, options={"default"=0})
     * @Groups({"answer:read", "question:read", "answer:edit"})
     */
    private $approved = false;

    /**
     * @var Question
     *
     * @ORM\ManyToOne(targetEntity="Question", inversedBy="answers")
     * @ORM\JoinColumn(name="question_id", referencedColumnName="id")
     * @Groups({"answer:read", "answer:edit"})
     * @Assert\NotBlank()
     */
    private $question;

    /**
     * @var string
     *
     * @Groups({"answer:read", "question:read"})
     */
    private $username;

    /**
     * @var int
     *
     * @Groups({"answer:read", "question:read"})
     */
    private $userId;

    /**
     * @var DateTimeInterface
     *
     * @Groups({"answer:read", "question:read"})
     */
    private $timestamp;

    /**
     * Answer constructor.
     *
     * @param string|null $answer
     * @param bool|null $approved
     * @param Question|null $question
     */
    public function __construct(
        ?string $answer = null,
        ?bool $approved = false,
        ?Question $question = null
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

    public function getUsername(): string
    {
        return $this->getCreatedBy()->getUsername();
    }

    public function getUserId(): int
    {
        return $this->getCreatedBy()->getId();
    }

    public function getTimestamp(): ?DateTimeInterface
    {
        return $this->getUpdatedAt();
    }
}
