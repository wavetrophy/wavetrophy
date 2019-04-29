<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\CreateMediaAction;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


/**
 * @ORM\Entity
 * @ApiResource(iri="http://schema.org/MediaObject", collectionOperations={
 *     "get",
 *     "post"={
 *         "method"="POST",
 *         "path"="/media",
 *         "controller"=CreateMediaAction::class,
 *         "defaults"={"_api_receive"=false},
 *     },
 * })
 * @Vich\Uploadable
 */
class Media
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
     * @var File|null
     * @Assert\NotNull()
     * @Vich\UploadableField(mapping="media", fileNameProperty="name")
     */
    private $file;

    /**
     * @var string|null
     * @ORM\Column(nullable=true)
     * @Groups({"question:read"})
     */
    private $name;

    /**
     * @var string|null
     * @ORM\Column(nullable=true)
     * @Groups({"question:read"})
     */
    private $path;

    /**
     * @var string|null
     * @ORM\Column(nullable=true)
     * @Groups({"question:read"})
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

    public function getId(): int
    {
        return $this->id;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(?File $file): void
    {
        $this->file = $file;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): void
    {
        $this->url = $url;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(?string $path): void
    {
        $this->path = $path;
    }

    public function asArray()
    {
        return [
            'id' => $this->id,
// Excluded to not let out security relevant data
//            'file' => $this->file,
            'name' => $this->name,
            'path' => $this->path,
            'url' => $this->url,
        ];
    }
}
