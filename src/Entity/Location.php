<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Entity\Traits\MetaFieldTrait;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Location
 *
 * @ORM\Table(name="location")
 * @ORM\Entity
 * @ApiResource(
 *     normalizationContext={
 *         "groups"={"location:read"},
 *         "enable_max_depth"=true,
 *     },
 *     denormalizationContext={
 *         "groups"={"location:edit"}
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
class Location
{
    use MetaFieldTrait;
    protected $iHaveSetTheSoftDeletedAnnotation = true;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups({"location:read"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=80, nullable=false)
     * @Groups({"location:read", "location:edit"})
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="lat", type="string", length=80, nullable=false)
     * @Groups({"location:read", "location:edit"})
     */
    private $lat;

    /**
     * @var string
     *
     * @ORM\Column(name="lon", type="string", length=80, nullable=false)
     * @Groups({"location:read", "location:edit"})
     */
    private $lon;

    /**
     * @var Wave
     *
     * @ORM\ManyToOne(targetEntity="Wave", inversedBy="locations")
     * @ORM\JoinColumn(name="wave_id", referencedColumnName="id")
     * @Groups({"location:read", "location:edit"})
     */
    private $wave;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Event", mappedBy="location")
     * @ApiSubresource(maxDepth=1)
     * @Groups({"location:read", "location:edit"})
     */
    private $events;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="TeamParticipation", mappedBy="location")
     * @ApiSubresource()
     * @Groups({"location:read", "location:edit"})
     */
    private $teamParticipations;

    /**
     * Location constructor.
     *
     * @param string|null $name
     * @param string|null $lat
     * @param string|null $lon
     * @param Wave|null $wave
     */
    public function __construct(
        ?string $name = null,
        ?string $lat = null,
        ?string $lon = null,
        ?Wave $wave = null
    ) {
        $this->name = $name;
        $this->lat = $lat;
        $this->lon = $lon;
        $this->wave = $wave;
    }

    public function __toString(): ?string
    {
        return $this->getName() . " (" . $this->getWave()->getName() . ")";
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

    public function setLocation(?string $location): self
    {
        $str = explode(',', $location);
        $this->setLat($str[0]);
        $this->setLon($str[1]);

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->getLat() . "," . $this->getLon();
    }

    public function getLat(): ?string
    {
        return $this->lat;
    }

    public function setLat(?string $lat): self
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLon(): ?string
    {
        return $this->lon;
    }

    public function setLon(?string $lon): self
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

    public function getEvents(): ?Collection
    {
        return $this->events;
    }

    public function addEvent(?Event $event): self
    {
        $this->events->add($event);

        return $this;
    }

    public function removeEvent(?Event $event): self
    {
        $this->events->removeElement($event);

        return $this;
    }

    public function getTeamParticipations(): ?Collection
    {
        return $this->teamParticipations;
    }

    public function addTeamParticipation(?TeamParticipation $teamParticipation): self
    {
        $this->teamParticipations->add($teamParticipation);

        return $this;
    }

    public function removeTeamParticipation(?TeamParticipation $teamParticipation): self
    {
        $this->teamParticipations->removeElement($teamParticipation);

        return $this;
    }
}
