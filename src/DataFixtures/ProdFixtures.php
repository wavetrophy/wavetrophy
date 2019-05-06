<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\UserEmail;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

/**
 * Class ProdFixtures
 */
class ProdFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $admin = new User('admin', 'Björn', 'Pfoster');
        $admin->setPlainPassword(getenv('ADMINSTRATOR_PASSWORD'));
        $admin->addEmails(new UserEmail('bjoern@pfoster.ch', false, true));
    }
}
