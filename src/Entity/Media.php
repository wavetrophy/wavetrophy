<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\CreateMediaAction;
use App\Entity\Traits\MetaFieldTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


/**
 * @ORM\Entity(repositoryClass="App\Repository\MediaRepository")
 * @ApiResource(
 *     iri="http://schema.org/MediaObject",
 *     normalizationContext={
 *         "groups"={"media:read"},
 *         "enable_max_depth"=true,
 *     },
 *     denormalizationContext={
 *         "groups"={"media:edit"}
 *     },
 *     itemOperations={
 *         "get",
 *         "put"={"access_control"="(user.getId() == object.getCreatorId() or is_granted('ROLE_ADMIN'))"},
 *         "delete"={"access_control"="(user.getId() == object.getCreatorId() or is_granted('ROLE_ADMIN'))"},
 *     },
 *     collectionOperations={
 *         "get",
 *         "post"={
 *             "method"="POST",
 *             "path"="/media",
 *             "controller"=CreateMediaAction::class,
 *             "defaults"={"_api_receive"=false},
 *         },
 *     },
 * )
 * @Vich\Uploadable
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 */
class Media
{
    use MetaFieldTrait;
    protected $iHaveSetTheSoftDeletedAnnotation = true;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups({"media:read"})
     */
    private $id;

    /**
     * @var File|null
     * @Assert\NotNull()
     * @Vich\UploadableField(mapping="media", fileNameProperty="name")
     * @Groups({"media:read", "media:edit"})
     */
    private $file;

    /**
     * @var string|null
     * @ORM\Column(nullable=true)
     * @Groups({"media:read", "question:read", "media:edit"})
     */
    private $name;

    /**
     * @var string|null
     * @ORM\Column(nullable=true)
     * @Groups({"media:read", "question:read", "media:edit"})
     */
    private $path;

    /**
     * @var string|null
     * @ORM\Column(nullable=true)
     * @Groups({"media:read", "question:read", "media:edit"})
     */
    private $url;

    /**
     * Media constructor.
     *
     * @param File|null $file
     * @param string|null $url
     */
    public function __construct(?File $file = null, ?string $url = null)
    {
        if (!empty($file)) {
            $this->file = $file;
        }
        if (!empty($url)) {
            $this->url = $url;
        }
    }

    /**
     * @return string|null
     */
    public function __toString()
    {
        return $this->getUrl() ?: '';
    }

    public function asArray()
    {
        return [
            'id' => $this->getUrl(),
            'path' => $this->getPath(),
            'url' => $this->getUrl(),
            'name' => $this->getName(),
        ];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(?File $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
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

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(?string $path): self
    {
        $this->path = $path;

        return $this;
    }
}
