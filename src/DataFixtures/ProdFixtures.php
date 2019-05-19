<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class ProdFixtures
 */
class ProdFixtures extends Fixture implements FixtureGroupInterface
{
    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $bjoern = new User();
        $bjoern->setCreatedBy($bjoern);
        $bjoern->setEmail('bjoern@pfoster.ch');
        $bjoern->setFirstName('Björn');
        $bjoern->setLastName('Pfoster');
        $bjoern->setPlainPassword('bjoern');
        $bjoern->setSuperAdmin(true);
        $bjoern->setRoles(['ROLE_DOCS', 'ROLE_SUPERADMIN', 'ROLE_ADMIN']);
        $bjoern->setLocale('de');
        $bjoern->setUsername('bjoern');
        $bjoern->setMustResetPassword(true);

        $louis = new User();
        $louis->setCreatedBy($bjoern);
        $louis->setEmail('louis@wavetrophy.com');
        $louis->setFirstName('Louis');
        $louis->setLastName('Palmer');
        $louis->setPlainPassword('louis');
        $louis->setSuperAdmin(true);
        $louis->setRoles(['ROLE_DOCS', 'ROLE_SUPERADMIN', 'ROLE_ADMIN']);
        $louis->setLocale('de');
        $louis->setUsername('louis');
        $louis->setMustResetPassword(true);

        $lea = new User();
        $lea->setCreatedBy($bjoern);
        $lea->setEmail('lea@wavetrophy.com');
        $lea->setFirstName('Lea');
        $lea->setLastName('Darányi');
        $lea->setPlainPassword('lea');
        $lea->setSuperAdmin(true);
        $lea->setRoles(['ROLE_DOCS', 'ROLE_SUPERADMIN', 'ROLE_ADMIN']);
        $lea->setLocale('de');
        $lea->setUsername('lea');
        $lea->setMustResetPassword(true);

        $manager->persist($bjoern);
        $manager->persist($louis);
        $manager->persist($lea);
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['prod'];
    }
}
