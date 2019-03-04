<?php

namespace App\Entity\Traits;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use DomainException;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;

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
     * @var string
     *
     * @ORM\Column(name="created_by", type="string", length=80, nullable=false, options={"comment"="The id of the
     *     user"})
     * @Groups({"readable"})
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(referencedColumnName="id")
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
     * @var string|null
     *
     * @ORM\Column(name="updated_by", type="string", length=80, nullable=true, options={"comment"="The user who
     *     updated the record"})
     * @Groups({"readable"})
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(referencedColumnName="id")
     * @Gedmo\Blameable(on="update")
     */
    private $updatedBy;

    /**
     * @var DateTime|null
     * @ORM\Column(name="deleted_at", type="datetime", nullable=true)
     * @Groups({"readable"})
     */
    protected $deletedAt;

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getCreatedBy(): ?string
    {
        return $this->createdBy;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function getUpdatedBy(): ?string
    {
        return $this->updatedBy;
    }

    public function getDeletedAt(): ?\DateTime
    {
        return $this->deletedAt;
    }

    public function isDeleted(): bool
    {
        return $this->deletedAt instanceof \DateTimeInterface;
    }

    public function recover()
    {
        $this->deletedAt = null;
    }
}
