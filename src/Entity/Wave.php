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
     * @ORM\OneToMany(targetEntity="Hotel", mappedBy="wave")
     * @ApiSubresource()
     * @Groups({"wave:read"})
     */
    private $hotels;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Event", mappedBy="wave")
     * @ApiSubresource()
     * @Groups({"wave:read"})
     */
    private $events;

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
        $this->hotels = new ArrayCollection();
        $this->events = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString(): ?string
    {
        return $this->getName();
    }

    public function toArray()
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'country' => $this->getCountry(),
            'start' => $this->getStartAsMoment()->format('Y-m-d[T]H:i:s.0000[Z]'),
            'end' => $this->getEndAsMoment()->format('Y-m-d[T]H:i:s.0000[Z]'),
        ];
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

    public function getStartAsMoment(): ?Moment
    {
        return new Moment($this->start->format('Y-m-d H:i:s'), 'UTC');
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

    public function getEndAsMoment(): ?Moment
    {
        return new Moment($this->end->format('Y-m-d H:i:s'), 'UTC');
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

    public function getHotels(): ?Collection
    {
        return $this->hotels;
    }

    public function addHotel(?Hotel $group): self
    {
        $this->hotels->add($group);

        return $this;
    }

    public function removeHotel(?Hotel $group): self
    {
        $this->hotels->removeElement($group);

        return $this;
    }

    public function getEvents(): ?Collection
    {
        return $this->events;
    }

    public function addEvent(?Event $group): self
    {
        $this->events->add($group);

        return $this;
    }

    public function removeEvent(?Event $group): self
    {
        $this->events->removeElement($group);

        return $this;
    }
}
