<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\MetaFieldTrait;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Hotel
 *
 * @ORM\Table(name="hotel", indexes={@ORM\Index(name="fk_hotel_location1_idx", columns={"location_id"})})
 * @ORM\Entity
 * @ApiResource()
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 */
class Hotel
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
     * @ORM\Column(name="name", type="string", length=80, nullable=false)
     */
    private $name;

    /**
     * @var bool
     *
     * @ORM\Column(name="breakfast_included", type="boolean", nullable=false, options={"default"="1"})
     */
    private $breakfastIncluded = true;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="last_check_in", type="datetime", nullable=true)
     */
    private $lastCheckIn;

    /**
     * @var string|null
     *
     * @ORM\Column(name="comment", type="text", length=0, nullable=true)
     */
    private $comment;

    /**
     * @var Location
     *
     * @ORM\ManyToOne(targetEntity="Location")
     * @ORM\JoinColumn(name="location_id", referencedColumnName="id")
     */
    private $location;

    /**
     * Hotel constructor.
     *
     * @param string|null $name
     * @param bool|null $breakfastIncluded
     * @param DateTimeInterface|null $lastCheckIn
     * @param string|null $comment
     * @param Location|null $location
     */
    public function __construct(
        ?string $name = null,
        ?bool $breakfastIncluded = null,
        ?DateTimeInterface $lastCheckIn = null,
        ?string $comment = null,
        ?Location $location = null
    ) {
        $this->name = $name;
        $this->breakfastIncluded = $breakfastIncluded;
        $this->lastCheckIn = $lastCheckIn;
        $this->comment = $comment;
        $this->location = $location;
    }

    public function __toString(): ?string
    {
        return $this->name;
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

    public function getBreakfastIncluded(): ?bool
    {
        return $this->breakfastIncluded;
    }

    public function setBreakfastIncluded(?bool $breakfastIncluded): self
    {
        $this->breakfastIncluded = $breakfastIncluded;

        return $this;
    }

    public function getLastCheckIn(): ?DateTimeInterface
    {
        return $this->lastCheckIn;
    }

    public function setLastCheckIn(?DateTimeInterface $lastCheckIn): self
    {
        $this->lastCheckIn = $lastCheckIn;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): self
    {
        $this->location = $location;

        return $this;
    }
}
