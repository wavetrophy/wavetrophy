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
use Moment\Moment;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Event
 *
 * @ORM\Table(name="event")
 * @ORM\Entity(repositoryClass="App\Repository\EventRepository")
 * @ApiResource(
 *     normalizationContext={
 *         "groups"={"event:read"},
 *         "enable_max_depth"=true,
 *     },
 *     denormalizationContext={
 *         "groups"={"event:edit"}
 *     },
 *     itemOperations={
 *         "get",
 *         "put"={"access_control"="(user.getId() == object.getCreatorId() or is_granted('ROLE_ADMIN'))"},
 *         "delete"={"access_control"="(user.getId() == object.getCreatorId() or is_granted('ROLE_ADMIN'))"},
 *     },
 *     collectionOperations={"get", "post"},
 * )
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 * @Vich\Uploadable()
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
     * @Groups({"event:read"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=80, nullable=false)
     * @Groups({"event:read", "event:edit"})
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=250, nullable=true)
     * @Groups({"event:read", "event:edit"})
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
     * @Groups({"event:read", "event:edit"})
     */
    private $end;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     * @Groups({"editable", "location:edit", "readable", "location:read"})
     */
    private $thumbnail;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @var string
     * @Groups({"editable", "location:edit", "readable", "location:read"})
     */
    private $thumbnailUrl;

    /**
     * @var File
     * @Vich\UploadableField(mapping="event_thumbnail", fileNameProperty="thumbnail")
     * @Assert\File()
     * @Groups({"editable", "location:edit", "readable", "location:read"})
     */
    private $thumbnailImage;

    /**
     * @var string
     *
     * @ORM\Column(name="lon", type="string", length=20, nullable=false)
     * @Groups({"event:read", "event:edit"})
     */
    private $lat;

    /**
     * @var string
     *
     * @ORM\Column(name="lat", type="string", length=20, nullable=false)
     * @Groups({"event:read", "even:edit"})
     */
    private $lon;

    /**
     * @var Wave|null
     *
     * @ORM\ManyToOne(targetEntity="Wave", inversedBy="events")
     * @ORM\JoinColumn(name="wave_id", referencedColumnName="id")
     * @Groups({"event:read", "event:edit"})
     */
    private $wave;


    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="EventParticipation", mappedBy="event", cascade={"all"})
     * @ApiSubresource(maxDepth=1)
     * @MaxDepth(1)
     * @Groups({"event:read", "event:edit"})
     */
    private $participations;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\EventActivity", mappedBy="event", orphanRemoval=true)
     */
    private $eventActivities;

    /**
     * Event constructor.
     *
     * @param string|null $name
     * @param string|null $description
     * @param Wave|null $wave
     * @param DateTimeInterface|null $start
     * @param DateTimeInterface|null $end
     * @param string|null $lat
     * @param string|null $lon
     */
    public function __construct(
        ?string $name = null,
        ?string $description = null,
        ?Wave $wave = null,
        ?DateTimeInterface $start = null,
        ?DateTimeInterface $end = null,
        ?string $lat = null,
        ?string $lon = null
    ) {
        $this->name = $name;
        $this->description = $description;
        $this->wave = $wave;
        $this->start = $start;
        $this->end = $end;
        $this->lat = $lat;
        $this->lon = $lon;
        $this->eventActivities = new ArrayCollection();
        $this->participations = new ArrayCollection();
    }

    public function __toString(): ?string
    {
        return (string)$this->name;
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

    public function getStart(): ?DateTime
    {
        return $this->start;
    }

    public function getStartAsMoment(): ?Moment
    {
        return new Moment($this->start->format('Y-m-d H:i:s'), 'Europe/Zurich');
    }

    public function setStart(?DateTimeInterface $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function getEnd(): ?DateTime
    {
        return $this->end;
    }

    public function getEndAsMoment(): ?Moment
    {
        return new Moment($this->end->format('Y-m-d H:i:s'), 'Europe/Zurich');
    }


    public function setEnd(?DateTimeInterface $end): self
    {
        $this->end = $end;

        return $this;
    }

    public function getThumbnail(): ?string
    {
        return $this->thumbnail;
    }

    public function setThumbnail(?string $thumbnail): self
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }

    public function getThumbnailUrl(): ?string
    {
        return $this->thumbnailUrl;
    }

    public function setThumbnailUrl(?string $thumbnailPath): self
    {
        $this->thumbnailUrl = $thumbnailPath;

        return $this;
    }

    public function getThumbnailImage(): ?File
    {
        return $this->thumbnailImage;
    }

    public function setThumbnailImage(?File $thumbnailImage): self
    {
        $this->thumbnailImage = $thumbnailImage;

        return $this;
    }

    public function setLocation(?string $location): self
    {
        $str = explode(',', $location);
        $this->setLat($str[0]);
        $this->setLon($str[1]);

        return $this;
    }

    public function getLocation(): ?string
    {
        if (empty($this->getLat()) || empty($this->getLon())) {
            return null;
        }
        return $this->getLat() . "," . $this->getLon();
    }

    public function getLat(): ?string
    {
        return $this->lat;
    }

    public function setLat(string $lat): self
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLon(): ?string
    {
        return $this->lon;
    }

    public function setLon(string $lon): self
    {
        $this->lon = $lon;

        return $this;
    }

    public function getWave(): ?Wave
    {
        return $this->wave;
    }

    public function setWave(?Wave $wave): self
    {
        $this->wave = $wave;

        return $this;
    }

    public function getParticipations(): ?Collection
    {
        return $this->participations;
    }

    public function addParticipation(?EventParticipation $participation): self
    {
        $this->participations->add($participation);

        return $this;
    }

    public function removeParticipation(?EventParticipation $participation): self
    {
        $this->participations->removeElement($participation);

        return $this;
    }

    /**
     * @return Collection|EventActivity[]
     */
    public function getEventActivities(): ?Collection
    {
        return $this->eventActivities;
    }

    public function addEventActivity(EventActivity $eventActivity): self
    {
        if (!$this->eventActivities->contains($eventActivity)) {
            $this->eventActivities[] = $eventActivity;
            $eventActivity->setEvent($this);
        }

        return $this;
    }

    public function removeEventActivity(EventActivity $eventActivity): self
    {
        if ($this->eventActivities->contains($eventActivity)) {
            $this->eventActivities->removeElement($eventActivity);
            // set the owning side to null (unless already changed)
            if ($eventActivity->getEvent() === $this) {
                $eventActivity->setEvent(null);
            }
        }

        return $this;
    }
}
