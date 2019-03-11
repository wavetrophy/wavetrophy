<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gesdinet\JWTRefreshTokenBundle\Entity\AbstractRefreshToken;

/**
 * Class JsonWebTokens
 *
 * This class override Gesdinet\JWTRefreshTokenBundle\Entity\RefreshToken to have another table name.
 *
 * @ORM\Table("jwt")
 * @ORM\Entity
 */
class JWT extends AbstractRefreshToken
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }
}