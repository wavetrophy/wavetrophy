<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\MetaFieldTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * UserPhonenumber
 *
 * @ORM\Table(name="user_phonenumber", indexes={@ORM\Index(name="fk_user_phonenumber_user1_idx", columns={"user_id"})})
 * @ORM\Entity
 * @ApiResource()
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
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
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="phonenumber", type="string", length=30, nullable=false,
     *      options={"comment"="The phone number of the user like +41765410128 (without spaces)"}
     * )
     */
    private $phonenumber;

    /**
     * @var string
     *
     * @ORM\Column(name="country_code", type="string", length=5, nullable=false,
     *     options={"comment"="The Country code of the phone number (e.g. +41 or +1 or +502)"}
     *)
     */
    private $countryCode;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_public", type="boolean", nullable=false)
     */
    private $isPublic = false;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User",inversedBy="phonenumbers")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
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
    }

    public function __toString(): ?string
    {
        return $this->countryCode . " " . $this->phonenumber;
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
        $this->phonenumber = $phonenumber;

        return $this;
    }

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function setCountryCode(?string $countryCode): self
    {
        $this->countryCode = $countryCode;

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
