<?php

namespace App\Entity\Traits;

use App\Entity\User;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use DomainException;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

/**
 * Trait MetaFieldTrait
 */
trait MetaFieldTrait
{
    /**
     * This is the most useless variable i've ever created.
     * But it is really required to remember the programmer to
     * add the Gedmo\SoftDeleteable(fieldName="deletedAt") annotation
     *
     * The usage of a to do would be wrong...
     *
     * @var bool
     *
     * @see https://github.com/Atlantic18/DoctrineExtensions/issues/1994
     *
     * MetaFieldTrait constructor.
     */
    public function __construct()
    {
        if ($this->iHaveSetTheSoftDeletedAnnotation !== true) {
            throw new DomainException('You must set the Gedmo\SoftDeleteable(fieldName="deletedAt") on the entity that uses the MetaFieldTrait');
        }
    }

    /**
     * @var DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     * @Groups({"readable"})
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="created_by", referencedColumnName="id", nullable=true)
     * @MaxDepth(1)
     * @Groups({"readable"})
     * @Gedmo\Blameable(on="create")
     */
    private $createdBy;

    /**
     * @var DateTime|null
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     * @Groups({"readable"})
     * @Gedmo\Timestampable(on="update")
     */
    private $updatedAt;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(name="updated_by", referencedColumnName="id", nullable=true)
     * @MaxDepth(1)
     * @Groups({"readable"})
     * @Gedmo\Blameable(on="update")
     */
    private $updatedBy;

    /**
     * @var DateTime|null
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     * @Groups({"readable"})
     */
    protected $deletedAt;

    public function setCreatedAt(?DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedBy(?User $user): self
    {
        $this->createdBy = $user;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setUpdatedAt(?DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedBy(?User $user): self
    {
        $this->updatedBy = $user;

        return $this;
    }

    public function getUpdatedBy(): ?User
    {
        return $this->updatedBy;
    }

    public function setDeletedAt(?DateTimeInterface $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    public function getDeletedAt(): ?DateTimeInterface
    {
        return $this->deletedAt;
    }

    public function isDeleted(): bool
    {
        return $this->deletedAt instanceof DateTimeInterface;
    }

    public function recover()
    {
        $this->deletedAt = null;
    }

    public function delete()
    {
        $this->setDeletedAt(new DateTime());
    }
}
