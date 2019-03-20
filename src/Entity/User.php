<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Entity\Traits\MetaFieldTrait;
use App\Validators\Constraint\UniqueEmail;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="user", indexes={@ORM\Index(name="fk_user_team1_idx", columns={"team_id"})})
 * @ORM\Entity
 * @ApiResource(
 *     normalizationContext={"groups"={"readable"}},
 *     denormalizationContext={"groups"={"editable"}}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 *
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 */
class User extends BaseUser implements UserInterface
{
    use MetaFieldTrait;
    protected $iHaveSetTheSoftDeletedAnnotation = true;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @ORM\Column(type="integer")
     * @Groups({"readable"})
     */
    protected $id;

    /**
     * @Groups({"editable", "readable"})
     * @Assert\NotBlank()
     * @Assert\Email()
     * @UniqueEmail()
     */
    protected $email;

    /**
     * @Groups({"readable"})
     */
    protected $username;

    /**
     * @var bool
     *
     * @Groups({"editable", "readable"})
     */
    protected $enabled = true;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=80, nullable=false)
     * @Groups({"editable", "readable"})
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=80, nullable=false)
     * @Groups({"editable", "readable"})
     */
    private $lastName;

    /**
     * @var bool
     *
     * @ORM\Column(name="has_received_welcome_email", type="boolean", length=1, nullable=false,
     *     options={"comment"="Indicates if the user already received his welcome email"})
     * @Groups({"readable"})
     */
    private $hasReceivedWelcomeEmail = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="has_received_setup_app_email", type="boolean", length=1, nullable=false,
     *     options={"comment"="Indicates if the user already received his setup app email"})
     * @Groups({"readable"})
     */
    private $hasReceivedSetupAppEmail = false;

    /**
     * @var Team
     *
     * @ORM\ManyToOne(targetEntity="Team", inversedBy="users")
     * @ORM\JoinColumn(name="team_id", referencedColumnName="id")
     * @Groups({"editable", "readable"})
     */
    private $team;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="UserEmail", mappedBy="user", cascade={"persist"})
     * @ApiSubresource()
     * @Groups({"editable", "readable"})
     */
    private $emails;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="UserPhonenumber", mappedBy="user", cascade={"persist"})
     * @ApiSubresource()
     * @Groups({"editable", "readable"})
     */
    private $phonenumbers;

    /**
     * User constructor.
     *
     * @param string|null $email
     * @param string|null $firstName
     * @param string|null $lastName
     * @param Team|null $team
     */
    public function __construct(
        ?string $email = null,
        ?string $firstName = null,
        ?string $lastName = null,
        ?Team $team = null
    ) {
        parent::__construct();

        $this->setEnabled(true);
        $this->setPlainPassword(uniqid());
        $this->emails = new ArrayCollection();
        $this->phonenumbers = new ArrayCollection();

        if (!empty($email)) {
            $this->setEmail($email);
        }
        if (!empty($firstName)) {
            $this->setFirstName($firstName);
        }
        if (!empty($lastName)) {
            $this->setLastName($lastName);
        }
        if (!empty($team)) {
            $this->setTeam($team);
        }
    }

    public function __toString(): ?string
    {
        return "{$this->firstName} {$this->lastName}";
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getHasReceivedWelcomeEmail(): ?bool
    {
        return $this->hasReceivedWelcomeEmail;
    }

    public function setHasReceivedWelcomeEmail(?bool $hasReceivedWelcomeEmail): self
    {
        $this->hasReceivedWelcomeEmail = $hasReceivedWelcomeEmail;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasReceivedSetupAppEmail(): bool
    {
        return $this->hasReceivedSetupAppEmail;
    }

    /**
     * @param bool $hasReceivedSetupAppEmail
     */
    public function setHasReceivedSetupAppEmail(bool $hasReceivedSetupAppEmail): void
    {
        $this->hasReceivedSetupAppEmail = $hasReceivedSetupAppEmail;
    }

    public function getTeam(): ?Team
    {
        return $this->team;
    }

    public function setTeam(?Team $team): self
    {
        $this->team = $team;

        return $this;
    }

    public function setEmail($email): self
    {
        parent::setEmail($email);
        parent::setUsername($email);

        $userEmail = new UserEmail($email, false, false, null, $this);

        $exist = $this->emails->exists(function ($key, UserEmail $email) use ($userEmail) {
            return $email->getEmail() === $userEmail->getEmail();
        });
        if (!$exist) {
            $this->emails->add($userEmail);
        }

        return $this;
    }

    public function getEmail(): ?UserEmail
    {
        return $this->getEmails()->first() ?: null;
    }

    public function getEmails(): ?Collection
    {
        return $this->emails;
    }

    public function addEmail(?UserEmail $email): self
    {
        $this->emails->add($email);

        if (empty($this->email)) {
            $this->setEmail($email->getEmail());
        }

        return $this;
    }

    public function removeEmail(?UserEmail $email): self
    {
        $this->emails->removeElement($email);

        return $this;
    }

    public function getPhonenumbers(): ?Collection
    {
        return $this->phonenumbers;
    }

    public function addPhonenumber(?UserPhonenumber $phonenumber): self
    {
        $this->phonenumbers->add($phonenumber);

        return $this;
    }

    public function removePhonenumber(?UserPhonenumber $phonenumber): self
    {
        $this->phonenumbers->removeElement($phonenumber);

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string)$this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        $this->plainPassword = null;
    }
}
