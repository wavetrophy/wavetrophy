<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use mysql_xdevapi\Collection;

/**
 * Lodging
 *
 * @ORM\Table(name="lodging", indexes={@ORM\Index(name="fk_lodging_hotel1_idx", columns={"hotel_id"})})
 * @ORM\Entity
 * @ApiResource()
 */
class Lodging
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string|null
     *
     * @ORM\Column(name="comment", type="string", length=1000, nullable=true,
     *      options={"comment"="Some personal information for the user"})
     */
    private $comment;

    /**
     * @var Hotel
     *
     * @ORM\ManyToOne(targetEntity="Hotel")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="hotel_id", referencedColumnName="id")
     * })
     */
    private $hotel;

    /**
     * @var Collection The users who are lodging in a hotel
     *
     * @ORM\ManyToMany(targetEntity="User")
     */
    private $users;

    /**
     * Lodging constructor.
     *
     * @param string|null $comment
     * @param Hotel|null $hotel
     */
    public function __construct(
        ?string $comment,
        ?Hotel $hotel
    ) {
        $this->comment = $comment;
        $this->hotel = $hotel;
        $this->users = new ArrayCollection();
    }

    public function __toString(): ?string
    {
        return $this->hotel->getName() . " " .  $this->comment;
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
