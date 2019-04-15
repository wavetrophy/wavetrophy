<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\MetaFieldTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * UserEmail
 *
 * @ORM\Table(name="user_email", indexes={@ORM\Index(name="fk_user_email_user1_idx", columns={"user_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\UserEmailRepository")
 * @ApiResource(
 *     normalizationContext={
 *         "groups"={"useremail.read"},
 *         "enable_max_depth"=true,
 *     },
 *     denormalizationContext={
 *         "groups"={"useremail.edit"}
 *     },
 * )
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 * @UniqueEntity(fields={"email"}, message="Email already registered")
 */
class UserEmail
{
    use MetaFieldTrait;
    protected $iHaveSetTheSoftDeletedAnnotation = true;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups({"readable","useremail.read"})
     */
    private $id;

    /**
     * @var string
     *
     * @Assert\Email(message="Please use a valid email")
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     * @Groups({"readable", "useremail.read", "editable", "useremail.edit"})
     */
    private $email;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_public", type="boolean", nullable=false, options={"default"="1"})
     * @Groups({"readable", "useremail.read", "editable", "useremail.edit"})
     */
    private $isPublic = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="confirmed", type="boolean", nullable=false, options={"default"="1"})
     * @Groups({"readable", "useremail.read"})
     */
    private $confirmed = false;

    /**
     * @var string
     *
     * @ORM\Column(name="confirmation_token", type="string", length=255, nullable=true)
     */
    private $confirmationToken;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="emails")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @MaxDepth(1)
     * @Groups({"readable", "useremail.read", "editable", "useremail.edit"})
     */
    private $user;

    /**
     * @var bool
     * @Groups({"readable", "useremail.read"})
     */
    private $isPrimary;

    /**
     * UserPhonenumber constructor.
     *
     * @param string|null $email
     * @param bool|null $isPublic
     * @param bool|null $confirmed
     * @param string|null $confirmationToken
     * @param User|null $user
     */
    public function __construct(
        ?string $email = null,
        ?bool $isPublic = null,
        ?bool $confirmed = false,
        ?string $confirmationToken = null,
        ?User $user = null
    ) {
        $this->email = $email;
        $this->isPublic = $isPublic;
        $this->confirmed = $confirmed;
        $this->confirmationToken = $confirmationToken;
        $this->user = $user;
    }

    public function __toString(): ?string
    {
        return $this->email;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getIsPublic(): ?bool
    {
        return $this->isPublic;
    }

    public function setIsPublic(?bool $isPublic): self
    {
        $this->isPublic = $isPublic;

        return $this;
    }

    public function isConfirmed(): bool
    {
        return $this->confirmed;
    }

    public function setConfirmed(bool $confirmed): void
    {
        $this->confirmed = $confirmed;
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

    public function getConfirmationToken(): ?string
    {
        return $this->confirmationToken;
    }

    public function setConfirmationToken(?string $confirmationToken): void
    {
        $this->confirmationToken = $confirmationToken;
    }

    public function getIsPrimary()
    {
        $email = $this->user->getEmail();
        return $email === $this->email;
    }
}
