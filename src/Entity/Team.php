<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Entity\Traits\MetaFieldTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * Team
 *
 * @ORM\Table(name="team", indexes={@ORM\Index(name="fk_team_group1_idx", columns={"group_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\TeamRespository")
 * @ApiResource(
 *     normalizationContext={
 *         "groups"={"team:read"},
 *         "enable_max_depth"=true,
 *     },
 *     denormalizationContext={
 *         "groups"={"team:edit"}
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
class Team
{
    use MetaFieldTrait;
    protected $iHaveSetTheSoftDeletedAnnotation = true;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups({"team:read", "readable", "user:read"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=80, nullable=false)
     * @Groups({"team:read", "readable", "user:read", "team:edit"})
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="start_number", type="integer", nullable=false,
     *     options={"comment"="The start number of the team (like 56 or 2)"}
     * )
     * @Groups({"team:read", "readable", "user:read", "team:edit"})
     */
    private $startNumber;

    /**
     * @var Group
     *
     * @ORM\ManyToOne(targetEntity="Group", inversedBy="teams")
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id")
     * @Groups({"team:read", "readable", "user:read", "team:edit"})
     */
    private $group;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="User", mappedBy="team", cascade={"persist","remove"})
     * @ApiSubresource(maxDepth=1)
     * @MaxDepth(1)
     * @Groups({"team:read", "readable", "team:edit", "editable"})
     */
    private $users;

    /**
     * Team constructor.
     *
     * @param string|null $name
     * @param int|null $startNumber
     * @param Group|null $group
     */
    public function __construct(
        ?string $name = null,
        ?int $startNumber = null,
        ?Group $group = null
    ) {
        $this->name = $name;
        $this->startNumber = $startNumber;
        $this->group = $group;
        $this->users = new ArrayCollection();
    }

    public function __toString(): ?string
    {
        return "{$this->name} ({$this->startNumber})";
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

    public function getStartNumber(): ?int
    {
        return $this->startNumber;
    }

    public function setStartNumber(?int $startNumber): self
    {
        $this->startNumber = $startNumber;

        return $this;
    }

    public function getGroup(): ?Group
    {
        return $this->group;
    }

    public function setGroup(?Group $group): self
    {
        $this->group = $group;

        return $this;
    }

    public function getUsers(): ?Collection
    {
        return $this->users;
    }

    public function addUser(?User $user): self
    {
        $this->users->add($user);

        return $this;
    }

    public function removeUser(?User $user): self
    {
        $this->users->removeElement($user);

        return $this;
    }
}
