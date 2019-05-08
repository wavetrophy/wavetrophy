<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Entity\Traits\MetaFieldTrait;
use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Wave
 *
 * @ORM\Table(name="wave")
 * @ORM\Entity(repositoryClass="App\Repository\WaveRepository")
 * @ApiResource(
 *     normalizationContext={
 *         "groups"={"wave:read"},
 *         "enable_max_depth"=true,
 *     },
 *     denormalizationContext={
 *         "groups"={"wave:edit", "wave:edit"}
 *     },
 *     itemOperations={
 *         "get",
 *         "put"={"access_control"="(user.getId() == object.getCreatorId() or is_granted('ROLE_ADMIN'))"},
 *         "delete"={"access_control"="(user.getId() == object.getCreatorId() or is_granted('ROLE_ADMIN'))"},
 *     },
 *     collectionOperations={"get", "post"},
 * )
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 */
class Wave
{
    use MetaFieldTrait;
    protected $iHaveSetTheSoftDeletedAnnotation = true;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups({"wave:read", "wave:edit"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=80, nullable=false)
     * @Groups({"wave:read", "wave:edit"})
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=80, nullable=false)
     * @Groups({"wave:read", "wave:edit"})
     */
    private $country;

    /**
     * @var DateTime
     *
     * @ORM\Column(
     *     name="start",
     *     type="datetime",
     *     nullable=false,
     *     options={"comment"="The start for the participants, not support"}
     * )
     * @Groups({"wave:read", "wave:edit"})
     */
    private $start;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="end", type="datetime", nullable=false)
     * @Groups({"wave:read", "wave:edit"})
     */
    private $end;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Group", mappedBy="wave")
     * @ApiSubresource()
     * @Groups({"wave:read"})
     */
    private $groups;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Location", mappedBy="wave")
     * @ApiSubresource()
     * @Groups({"wave:read"})
     */
    private $locations;

    /**
     * Wave constructor.
     *
     * @param string|null $name
     * @param string|null $country
     * @param DateTimeInterface|null $start
     * @param DateTimeInterface|null $end
     */
    public function __construct(
        ?string $name = null,
        ?string $country = null,
        ?\DateTimeInterface $start = null,
        ?DateTimeInterface $end = null
    ) {
        $this->name = $name;
        $this->country = $country;
        $this->start = $start;
        $this->end = $end;
        $this->groups = new ArrayCollection();
        $this->locations = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString(): ?string
    {
        return $this->getName();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getStart(): ?DateTimeInterface
    {
        return $this->start;
    }

    public function setStart(?DateTimeInterface $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function getEnd(): ?DateTimeInterface
    {
        return $this->end;
    }

    public function setEnd(?DateTimeInterface $end): self
    {
        $this->end = $end;

        return $this;
    }

    public function getGroups(): ?Collection
    {
        return $this->groups;
    }

    public function addGroup(?Group $group): self
    {
        $this->groups->add($group);

        return $this;
    }

    public function removeGroup(?Group $group): self
    {
        $this->groups->removeElement($group);

        return $this;
    }

    public function getLocations(): ?Collection
    {
        return $this->locations;
    }

    public function addLocation(?Location $group): self
    {
        $this->locations->add($group);

        return $this;
    }

    public function removeLocation(?Location $group): self
    {
        $this->locations->removeElement($group);

        return $this;
    }
}
