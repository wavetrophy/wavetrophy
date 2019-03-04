<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Entity\Traits\MetaFieldTrait;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * TeamParticipation
 *
 * @ORM\Table(name="team_participation",
 *     indexes={
 *          @ORM\Index(name="fk_team_participation_location1_idx", columns={"location_id"})
 *     }
 * )
 * @ORM\Entity
 * @ApiResource()
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 */
class TeamParticipation
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
     * @var \DateTime
     *
     * @ORM\Column(name="arrival", type="datetime", nullable=false)
     */
    private $arrival;

    /**
     * @var string
     *
     * @ORM\Column(name="departure", type="string", length=80, nullable=false)
     */
    private $departure;

    /**
     * @var Location
     *
     * @ORM\ManyToOne(targetEntity="Location", inversedBy="teamParticipations")
     * @ORM\JoinColumn(name="location_id", referencedColumnName="id")
     */
    private $location;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="Team")
     */
    private $teams;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getArrival(): ?\DateTimeInterface
    {
        return $this->arrival;
    }

    public function setArrival(\DateTimeInterface $arrival): self
    {
        $this->arrival = $arrival;

        return $this;
    }

    public function getDeparture(): ?string
    {
        return $this->departure;
    }

    public function setDeparture(string $departure): self
    {
        $this->departure = $departure;

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

    public function getTeams(): Collection
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
