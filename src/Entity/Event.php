<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\MetaFieldTrait;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Event
 *
 * @ORM\Table(name="event", indexes={@ORM\Index(name="fk_event_location1_idx", columns={"location_id"})})
 * @ORM\Entity
 * @ApiResource()
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 */
class Event
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
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=250, nullable=true)
     */
    private $description;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="start", type="datetime", length=80, nullable=false)
     */
    private $start;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="end", type="datetime", length=80, nullable=false)
     */
    private $end;

    /**
     * @var Location
     *
     * @ORM\ManyToOne(targetEntity="Location",inversedBy="events")
     * @ORM\JoinColumn(name="location_id", referencedColumnName="id")
     */
    private $location;

    /**
     * Event constructor.
     *
     * @param string|null $name
     * @param string|null $description
     * @param DateTimeInterface|null $start
     * @param DateTimeInterface|null $end
     * @param Location|null $location
     */
    public function __construct(
        ?string $name,
        ?string $description,
        ?DateTimeInterface $start,
        ?DateTimeInterface $end,
        ?Location $location
    ) {
        $this->name = $name;
        $this->description = $description;
        $this->start = $start;
        $this->end = $end;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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
