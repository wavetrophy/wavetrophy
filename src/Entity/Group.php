<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Entity\Traits\MetaFieldTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Group
 *
 * @ORM\Table(name="`group`", indexes={@ORM\Index(name="fk_group_wave1_idx", columns={"wave_id"})})
 * @ORM\Entity
 * @ApiResource()
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 */
class Group
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
     * @var Wave
     *
     * @ORM\ManyToOne(targetEntity="Wave", inversedBy="groups", )
     * @ORM\JoinColumn(name="wave_id", referencedColumnName="id")
     */
    private $wave;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Team", mappedBy="group")
     * @ApiSubresource()
     */
    private $teams;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Question", mappedBy="group")
     * @ApiSubresource()
     */
    private $questions;

    /**
     * Group constructor.
     *
     * @param string|null $name
     * @param Wave|null $wave
     */
    public function __construct(
        ?string $name = null,
        ?Wave $wave = null
    ) {
        $this->name = $name;
        $this->wave = $wave;
        $this->teams = new ArrayCollection();
        $this->questions = new ArrayCollection();
    }

    public function __toString(): ?string
    {
        return $this->getName() . " " . $this->wave->getName();
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

    public function getWave(): ?Wave
    {
        return $this->wave;
    }

    public function setWave(?Wave $wave): self
    {
        $this->wave = $wave;

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

    public function getQuestions(): ?Collection
    {
        return $this->questions;
    }

    public function addQuestion(?Question $question): self
    {
        $this->questions->add($question);

        return $this;
    }

    public function removeQuestion(?Question $question): self
    {
        $this->questions->removeElement($question);

        return $this;
    }
}
