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

/**
 * Lodging
 *
 * @ORM\Table(name="lodging", indexes={@ORM\Index(name="fk_lodging_hotel1_idx", columns={"hotel_id"})})
 * @ORM\Entity(repositoryClass="App\Repository\LodgingRepository")
 * @ApiResource(
 *     normalizationContext={
 *         "groups"={"lodging:read"},
 *         "enable_max_depth"=true,
 *     },
 *     itemOperations={"get"},
 *     collectionOperations={"get"},
 * )
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 */
class Lodging
{
    use MetaFieldTrait;
    protected $iHaveSetTheSoftDeletedAnnotation = true;
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups({"lodging:read"})
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="comment", type="string", length=1000, nullable=true,
     *      options={"comment"="Some personal information for the user"})
     * @Groups({"lodging:read"})
     */
    private $comment;

    /**
     * @var Hotel
     *
     * @ORM\ManyToOne(targetEntity="Hotel", inversedBy="lodgings")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="hotel_id", referencedColumnName="id")
     * })
     * @Groups({"lodging:read"})
     */
    private $hotel;

    /**
     * @var Collection The users who are lodging in a hotel
     *
     * @ApiSubresource()
     * @ORM\ManyToMany(targetEntity="User")
     * @Groups({"lodging:read"})
     */
    private $users;

    /**
     * Lodging constructor.
     *
     * @param string|null $comment
     * @param Hotel|null $hotel
     */
    public function __construct(
        ?string $comment = null,
        ?Hotel $hotel = null
    ) {
        $this->comment = $comment;
        $this->hotel = $hotel;
        $this->users = new ArrayCollection();
    }

    public function __toString(): ?string
    {
        return (string)$this->hotel->getName() . ' lodging of ' . $this->users->count();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getHotel(): ?Hotel
    {
        return $this->hotel;
    }

    public function setHotel(?Hotel $hotel): self
    {
        $this->hotel = $hotel;

        return $this;
    }

    public function getUsers(): ?Collection
    {
        return $this->users;
    }

    public function addUser(?User $users): self
    {
        $this->users->add($users);

        return $this;
    }

    public function removeUser(?User $user): self
    {
        $this->users->removeElement($user);

        return $this;
    }
}
