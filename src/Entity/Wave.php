<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Entity\Traits\MetaFieldTrait;
use DateTime;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Wave
 *
 * @ORM\Table(name="wave")
 * @ORM\Entity
 * @ApiResource(attributes={"denormalization_context"={"groups"={"editable"}}})
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
     * @Groups({"editable", "readonly"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=80, nullable=false)
     * @Groups({"editable", "readonly"})
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=80, nullable=false)
     * @Groups({"editable", "readonly"})
     */
    private $country;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="start", type="datetime", nullable=false, options={"comment"="The start for the participants, not support"})
     * @Groups({"editable", "readonly"})
     */
    private $start;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="end", type="datetime", nullable=false)
     * @Groups({"editable", "readonly"})
     */
    private $end;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Group", mappedBy="wave")
     * @ApiSubresource()
     * @Groups({"readonly"})
     */
    private $groups;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Location", mappedBy="wave")
     * @ApiSubresource()
     * @Groups({"readonly"})
     */
    private $locations;

    public function __toString(): string
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

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getStart(): ?\DateTimeInterface
    {
        return $this->start;
    }

    public function setStart(\DateTimeInterface $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function getEnd(): ?\DateTimeInterface
    {
        return $this->end;
    }

    public function setEnd(\DateTimeInterface $end): self
    {
        $this->end = $end;

        return $this;
    }

    public function getGroups(): Collection
    {
        return $this->groups;
    }

    public function addGroup(Group $group): self
    {
        $this->groups->add($group);

        return $this;
    }

    public function removeGroup(Group $group): self
    {
        $this->groups->removeElement($group);

        return $this;
    }

    public function getLocations(): Collection
    {
        return $this->locations;
    }

    public function addLocation(Location $group): self
    {
        $this->locations->add($group);

        return $this;
    }

    public function removeLocation(Location $group): self
    {
        $this->locations->removeElement($group);

        return $this;
    }
}
