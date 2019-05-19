<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Entity\Traits\MetaFieldTrait;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Hotel
 *
 * @ORM\Table(name="hotel")
 * @ORM\Entity(repositoryClass="App\Repository\HotelRepository")
 * @ApiResource(
 *     normalizationContext={
 *         "groups"={"hotel:read"},
 *         "enable_max_depth"=true,
 *     },
 *     denormalizationContext={
 *         "groups"={"hotel:edit"}
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
class Hotel
{
    use MetaFieldTrait;
    protected $iHaveSetTheSoftDeletedAnnotation = true;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups({"hotel:read"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=80, nullable=false)
     * @Groups({"hotel:read", "hotel:edit"})
     */
    private $name;

    /**
     * @var bool
     *
     * @ORM\Column(name="breakfast_included", type="boolean", nullable=false, options={"default"="1"})
     * @Groups({"hotel:read", "hotel:edit"})
     */
    private $breakfastIncluded = true;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="check_in", type="datetime", nullable=false)

     * @Groups({"hotel:read", "hotel:edit"})
     */
    private $checkIn;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="check_out", type="datetime", nullable=true)
     * @Groups({"hotel:read", "hotel:edit"})
     */
    private $checkOut;

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
     * @Vich\UploadableField(mapping="hotel_thumbnail", fileNameProperty="thumbnail")
     * @Assert\File()
     * @Groups({"editable", "location:edit", "readable", "location:read"})
     */
    private $thumbnailImage;

    /**
     * @var string
     *
     * @ORM\Column(name="lon", type="string", length=20, nullable=false)
     * @Groups({"hotel:read", "hotel:edit"})
     */
    private $lat;

    /**
     * @var string
     *
     * @ORM\Column(name="lat", type="string", length=20, nullable=false)
     * @Groups({"hotel:read", "hotel:edit"})
     */
    private $lon;

    /**
     * @var Wave|null
     *
     * @ORM\ManyToOne(targetEntity="Wave", inversedBy="hotels")
     * @ORM\JoinColumn(name="wave_id", referencedColumnName="id")
     * @Groups({"location:read", "location:edit"})
     */
    private $wave;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="Lodging", mappedBy="hotel", cascade={"all"})
     * @ApiSubresource(maxDepth=1)
     * @MaxDepth(1)
     * @Groups({"hotel:read", "hotel:edit"})
     */
    private $lodgings;

    /**
     * Hotel constructor.
     *
     * @param Wave|null $wave
     * @param File|null $thumbnail
     * @param string|null $name
     * @param bool|null $breakfastIncluded
     * @param DateTimeInterface|null $checkIn
     * @param DateTimeInterface|null $checkOut
     * @param string|null $lat
     * @param string|null $lon
     */
    public function __construct(
        ?Wave $wave = null,
        ?File $thumbnail = null,
        ?string $name = null,
        ?bool $breakfastIncluded = null,
        ?DateTimeInterface $checkIn = null,
        ?DateTimeInterface $checkOut = null,
        ?string $lat = null,
        ?string $lon = null
    ) {
        $this->wave = $wave;
        $this->thumbnailImage = $thumbnail;
        $this->name = $name;
        $this->breakfastIncluded = $breakfastIncluded;
        $this->checkIn = $checkIn;
        $this->checkOut = $checkOut;
        $this->lat = $lat;
        $this->lon = $lon;
        $this->lodgings = new ArrayCollection();
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

    public function getBreakfastIncluded(): ?bool
    {
        return $this->breakfastIncluded;
    }

    public function setBreakfastIncluded(?bool $breakfastIncluded): self
    {
        $this->breakfastIncluded = $breakfastIncluded;

        return $this;
    }

    public function getCheckIn(): ?DateTimeInterface
    {
        return $this->checkIn;
    }

    public function setCheckIn(?DateTimeInterface $checkIn): self
    {
        $this->checkIn = $checkIn;

        return $this;
    }

    public function getCheckOut(): ?DateTimeInterface
    {
        return $this->checkOut;
    }

    public function setCheckOut(?DateTimeInterface $checkOut): self
    {
        $this->checkOut = $checkOut;

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

    public function getThumbnailUrl(): string
    {
        return $this->thumbnailUrl;
    }

    public function setThumbnailUrl(string $thumbnailPath): self
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
        return $this->getLat() . "," . $this->getLon();
    }

    public function getLat(): string
    {
        return $this->lat;
    }

    public function setLat(string $lat): self
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLon(): string
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

    public function setWave(?Wave $wave): void
    {
        $this->wave = $wave;
    }

    public function getLodgings(): Collection
    {
        return $this->lodgings;
    }

    public function addLodging(?Lodging $lodging): self
    {
        $this->lodgings->add($lodging);

        return $this;
    }

    public function removeLodging(?Lodging $lodging): self
    {
        $this->lodgings->removeElement($lodging);

        return $this;
    }
}
