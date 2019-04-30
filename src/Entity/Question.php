<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Entity\Traits\MetaFieldTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Question
 *
 * @ORM\Table(name="question", indexes={@ORM\Index(name="fk_question_user1_idx", columns={"user_id"}),
 *     @ORM\Index(name="fk_question_group1_idx", columns={"group_id"})})
 * @ORM\Entity
 * @ApiResource(
 *     attributes={"order"={"createdAt": "DESC"}},
 *     normalizationContext={
 *         "groups"={"question:read"},
 *         "enable_max_depth"=true,
 *     },
 *     denormalizationContext={
 *         "groups"={"question:edit"},
 *     }
 * )
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 */
class Question
{
    use MetaFieldTrait;
    protected $iHaveSetTheSoftDeletedAnnotation = true;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups({"question:read"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=40, nullable=false)
     * @Assert\NotBlank
     * @Assert\Length(max="40", min="10")
     * @Groups({"question:read", "question:edit"})
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="question", type="string", length=1000, nullable=false)
     * @Assert\Length(max="1000", min="20")
     * @Groups({"question:read", "question:edit"})
     */
    private $question;

    /**
     * @var bool
     *
     * @ORM\Column(name="resolved", type="boolean", nullable=false, options={"default"="0"})
     * @Groups({"question:read", "question:edit"})
     */
    private $resolved = false;

    /**
     * @var Group
     *
     * @ORM\ManyToOne(targetEntity="Group", inversedBy="questions")
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id", nullable=true)
     * @Groups({"question:read", "question:edit"})
     */
    private $group;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @Groups({"question:read", "question:edit"})
     */
    private $user;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Answer", mappedBy="question")
     * @ApiSubresource()
     * @Groups({"question:read", "question:edit"})
     */
    private $answers;

    /**
     * Question constructor.
     *
     * @param string|null $question
     * @param Group|null $group
     * @param User|null $user
     */
    public function __construct(
        ?string $title = null,
        ?string $question = null,
        ?Group $group = null,
        ?User $user = null
    ) {
        $this->title = $title;
        $this->question = $question;
        $this->group = $group;
        $this->user = $user;
        $this->setCreatedBy($user);
        $this->setUpdatedBy($user);
        $this->answers = new ArrayCollection();
    }

    public function __toString(): ?string
    {
        return $this->question;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getQuestion(): ?string
    {
        return $this->question;
    }

    public function setQuestion(?string $question): self
    {
        $this->question = $question;

        return $this;
    }

    public function getResolved(): bool
    {
        return $this->resolved;
    }

    public function setResolved(bool $resolved): void
    {
        $this->resolved = $resolved;
    }

    public function getGroup(): ?Group
    {
        return $this->group;
    }

    public function setGroup(?Group $group): self
    {
        $this->group = $group;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getAnswers(): ?Collection
    {
        return $this->answers;
    }

    public function addAnswer(?Answer $answer): self
    {
        $this->answers->add($answer);

        return $this;
    }

    public function removeAnswer(?Answer $answer): self
    {
        $this->answers->removeElement($answer);

        return $this;
    }
}
