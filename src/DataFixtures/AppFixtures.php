<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\UserEmail;
use App\Entity\Wave;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setFirstName("Björn");
        $user->setLastName("Pfoster");
        $user->setHasReceivedWelcomeEmail(true);
        $user->addRole('ROLE_ADMIN');

        $userEmail = new UserEmail();
        $userEmail->setEmail("bjoern@pfoster.ch");
        $userEmail->setIsPublic(true);
        $userEmail->setUser($user);

        $user->addEmail($userEmail);
        $user->setPlainPassword("bjoern");
        $user->setEnabled(true);
        $user->setSuperAdmin(true);

        $wave = new Wave();
        $wave->setName('WAVE Switzerland');
        $wave->setCountry('Switzerland');
        $wave->setStart(new DateTime('2019-06-14 10:00:00'));
        $wave->setEnd(new DateTime('2019-06-22 18:00:00'));

        $manager->persist($user);
        $manager->persist($userEmail);
        $manager->persist($wave);

        $manager->flush();
    }
}
