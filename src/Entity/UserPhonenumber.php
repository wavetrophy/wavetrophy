<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\MetaFieldTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * UserPhonenumber
 *
 * @ORM\Table(name="user_phonenumber", indexes={@ORM\Index(name="fk_user_phonenumber_user1_idx", columns={"user_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\UserPhonenumberRepository")
 * @ApiResource(
 *     normalizationContext={
 *         "groups"={"userphonenumber:read"},
 *         "enable_max_depth"=true,
 *     },
 *     denormalizationContext={
 *         "groups"={"userphonenumber.edit"}
 *     },
 *     itemOperations={
 *         "get",
 *         "put"={"access_control"="(user.getId() == object.getCreatorId() or is_granted('ROLE_ADMIN'))"},
 *         "delete"={"access_control"="(user.getId() == object.getCreatorId() or is_granted('ROLE_ADMIN'))",
 *     },
 *     collectionOperations={"get", "post"},
 * )
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 * @UniqueEntity(
 *     fields={"phonenumber", "countryCode"},
 *     repositoryMethod="isUnique",
 *     errorPath="phonenumber",
 *     message="Phonenumber already registered"
 * )
 * @ORM\HasLifecycleCallbacks
 */
class UserPhonenumber
{
    use MetaFieldTrait;
    protected $iHaveSetTheSoftDeletedAnnotation = true;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups({"readable", "user:read", "userphonenumber:read"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="phonenumber", type="string", length=30, nullable=false,
     *      options={"comment"="The phone number of the user like 765410128 (without spaces)"}
     * )
     * @Groups({"readable", "user:read", "userphonenumber:read", "editable", "userphonenumber.edit"})
     */
    private $phonenumber;

    /**
     * @var string
     *
     * @ORM\Column(name="country_code", type="string", length=5, nullable=false,
     *     options={"comment"="The Country code of the phone number (e.g. +41 or +1 or +502)"}
     *)
     * @Groups({"readable", "user:read", "userphonenumber:read", "editable", "userphonenumber.edit"})
     */
    private $countryCode;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_public", type="boolean", nullable=false)
     * @Groups({"readable", "user:read", "userphonenumber:read", "editable", "userphonenumber.edit"})
     */
    private $isPublic = false;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="phonenumbers")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @MaxDepth(1)
     * @Groups({"readable", "userphonenumber:read", "editable", "userphonenumber.edit"})
     */
    private $user;

    /**
     * UserPhonenumber constructor.
     *
     * @param string|null $phonenumber
     * @param string|null $countryCode
     * @param bool|null $isPublic
     * @param User|null $user
     */
    public function __construct(
        ?string $phonenumber = null,
        ?string $countryCode = null,
        ?bool $isPublic = null,
        ?User $user = null
    ) {
        $this->phonenumber = $phonenumber;
        $this->countryCode = $countryCode;
        $this->isPublic = $isPublic;
        $this->user = $user;
        $this->setCreatedBy($user);
        $this->setUpdatedBy($user);
    }

    public function __toString(): ?string
    {
        return $this->countryCode . " " . $this->phonenumber;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function handle()
    {
        $this->countryCode = preg_replace('/\s+/', '', trim($this->countryCode));
        $this->phonenumber = preg_replace('/\s+/', '', trim($this->phonenumber));
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPhonenumber(): ?string
    {
        return $this->phonenumber;
    }

    public function setPhonenumber(?string $phonenumber): self
    {
        $this->phonenumber = preg_replace('/\s+/', '', trim($phonenumber));;

        return $this;
    }

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function setCountryCode(?string $countryCode): self
    {
        $this->countryCode = preg_replace('/\s+/', '', trim($countryCode));

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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
