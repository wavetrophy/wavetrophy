<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\MetaFieldTrait;
use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Moment\Moment;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\EventActivityRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 */
class EventActivity
{
    use MetaFieldTrait;
    protected $iHaveSetTheSoftDeletedAnnotation = true;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $start;

    /**
     * @ORM\Column(type="datetime")
     */
    private $end;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Event", inversedBy="eventActivities")
     * @ORM\JoinColumn(nullable=false)
     */
    private $event;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * EventActivity constructor.
     *
     * @param DateTime|null $start
     * @param DateTime|null $end
     * @param string|null $title
     * @param string|null $description
     */
    public function __construct(
        ?DateTime $start = null,
        ?DateTime $end = null,
        ?string $title = null,
        ?string $description = null
    ) {
        $this->start = $start;
        $this->end = $end;
        $this->title = $title;
        $this->description = $description;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStart(): ?\DateTimeInterface
    {
        return $this->start;
    }

    public function getStartAsMoment(): ?Moment
    {
        return new Moment($this->start->format('Y-m-d H:i:s'),  'Europe/Zurich');
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

    public function getEndAsMoment(): ?Moment
    {
        return new Moment($this->end->format('Y-m-d H:i:s'),  'Europe/Zurich');
    }

    public function setEnd(\DateTimeInterface $end): self
    {
        $this->end = $end;

        return $this;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        $this->event = $event;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

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
}
