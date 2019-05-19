<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\MetaFieldTrait;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * eventparticipation
 *
 * @ORM\Table(name="event_participation")
 * @ORM\Entity(repositoryClass="App\Repository\EventParticipationRepository")
 * @ApiResource(
 *     normalizationContext={
 *         "groups"={"eventparticipation:read"},
 *         "enable_max_depth"=true,
 *     },
 *     denormalizationContext={
 *         "groups"={"eventparticipation:edit"}
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
class EventParticipation
{
    use MetaFieldTrait;
    protected $iHaveSetTheSoftDeletedAnnotation = true;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups({"eventparticipation:read"})
     */
    private $id;

    /**
     * @var DateTimeInterface
     *
     * @ORM\Column(name="arrival", type="datetime", nullable=false)
     * @Groups({"eventparticipation:read", "eventparticipation:edit"})
     */
    private $arrival;

    /**
     * @var DateTimeInterface
     *
     * @ORM\Column(name="departure", type="datetime", length=80, nullable=false)
     * @Groups({"eventparticipation:read", "eventparticipation:edit"})
     */
    private $departure;

    /**
     * @var Event
     *
     * @ORM\ManyToOne(targetEntity="Event", inversedBy="eventParticipations")
     * @ORM\JoinColumn(name="event_id", referencedColumnName="id")
     * @Groups({"eventparticipation:read", "eventparticipation:edit"})
     */
    private $event;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="Team", cascade={"persist"})
     * @ORM\JoinTable(name="team_has_participations")
     * @Groups({"eventparticipation:read", "eventparticipation:edit"})
     */
    private $teams;

    /**
     * eventparticipation constructor.
     *
     * @param DateTimeInterface|null $arrival
     * @param DateTimeInterface|null $departure
     * @param Event|null $event
     */
    public function __construct(
        ?DateTimeInterface $arrival = null,
        ?DateTimeInterface $departure = null,
        ?Event $event = null
    ) {
        $this->arrival = $arrival;
        $this->departure = $departure;
        $this->event = $event;
        $this->teams = new ArrayCollection();
    }

    public function __toString(): ?string
    {
        $name = $this->event->getName();
        $arrival = $this->arrival->format('d. M. H:i');
        $departure = $this->departure->format('d. M. H:i');
        return "{$name}: {$arrival} - {$departure}";
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArrival(): ?DateTimeInterface
    {
        return $this->arrival;
    }

    public function setArrival(?DateTimeInterface $arrival): self
    {
        $this->arrival = $arrival;

        return $this;
    }

    public function getDeparture(): ?DateTimeInterface
    {
        return $this->departure;
    }

    public function setDeparture(?DateTimeInterface $departure): self
    {
        $this->departure = $departure;

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

    public function getTeams(): ?Collection
    {
        return $this->teams;
    }

    public function addTeam(?Team $team): self
    {
        $this->teams->add($team);

        return $this;
    }

    public function removeTeam(?Team $team): self
    {
        $this->teams->removeElement($team);

        return $this;
    }
}
