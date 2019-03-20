<?php

namespace App\DataFixtures;

use App\Entity\Event;
use App\Entity\Group;
use App\Entity\Hotel;
use App\Entity\Location;
use App\Entity\Lodging;
use App\Entity\Team;
use App\Entity\TeamParticipation;
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
        $userEmail->setEmail("bjoern");
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

        $lucerne = new Location('Luzern', '47.03892207982464', '8.318022908459511', $wave);
        $lucerneGreeting = new Event(
            'Begrüssung',
            'Die Begrüssung im Theater',
            new DateTime('2019-06-14 10:00:00'),
            new DateTime('2019-06-14 10:45:00'),
            $lucerne
        );
        $lucerneShow = new Event(
            'Auto Show',
            'Eine Auto Show aller Autos der WAVE',
            new DateTime('2019-06-14 11:00:00'),
            new DateTime('2019-06-14 11:45:00'),
            $lucerne
        );
        $lucerneLunch = new Event(
            'Lunch',
            null,
            new DateTime('2019-06-14 12:00:00'),
            new DateTime('2019-06-14 13:00:00'),
            $lucerne
        );
        $lucerneDeparture = new Event(
            'Abfahrt',
            'Abfahrt aller Autos im 2 Minuten Rhytmus',
            new DateTime('2019-06-14 14:00:00'),
            new DateTime('2019-06-14 14:30:00'),
            $lucerne
        );

        $basel = new Location('Basel', '47.529612457152716', '7.583999021162867', $wave);
        $baselGreeting = new Event(
            'Begrüssung',
            'Die Begrüssung im Dreispitz',
            new DateTime('2019-06-14 15:00:00'),
            new DateTime('2019-06-14 15:45:00'),
            $basel
        );
        $baselShow = new Event(
            'Führung',
            'Die Führung durch das Dreispitzareal',
            new DateTime('2019-06-14 16:00:00'),
            new DateTime('2019-06-14 16:45:00'),
            $basel
        );
        $baselLunch = new Event(
            'Abendessen',
            null,
            new DateTime('2019-06-14 18:00:00'),
            new DateTime('2019-06-14 19:00:00'),
            $basel
        );
        $baselDeparture = new Event(
            'Abfahrt',
            'Abfahrt aller Autos im 2 Minuten Rhytmus',
            new DateTime('2019-06-14 20:00:00'),
            new DateTime('2019-06-14 20:30:00'),
            $basel
        );
        $manager->persist($lucerne);
        $manager->persist($basel);
        $manager->persist($lucerneGreeting);
        $manager->persist($lucerneShow);
        $manager->persist($lucerneLunch);
        $manager->persist($lucerneDeparture);
        $manager->persist($baselGreeting);
        $manager->persist($baselShow);
        $manager->persist($baselLunch);
        $manager->persist($baselDeparture);

        $group1 = new Group('Gruppe 1', $wave);
        $team1 = new Team('Renault', 1, $group1);
        $lorenz = new User('bjoern@pfoster.ch', 'Lorenz', 'Camenisch', $team1);
        $remo = new User('remo@example.com', 'Remo', 'Camenisch', $team1);

        $team2 = new Team('Pilatus', 2, $group1);
        $andy = new User('andy@example.com', 'Andy', 'Alig', $team2);
        $edy = new User('edy@example.com', 'Edy', 'Künzli', $team2);

        $group2 = new Group('Gruppe 2', $wave);
        $team3 = new Team('Jura Energie', 3, $group2);
        $jean = new User('jean@example.com', 'Jean', 'Oppliger', $team3);
        $esther = new User('esther@example.com', 'Esther', 'Oppliger', $team3);

        $manager->persist($group1);
        $manager->persist($group2);
        $manager->persist($team1);
        $manager->persist($team2);
        $manager->persist($team3);
        $manager->persist($lorenz);
        $manager->persist($remo);
        $manager->persist($andy);
        $manager->persist($edy);
        $manager->persist($jean);
        $manager->persist($esther);

        $team2Basel = new TeamParticipation(
            new DateTime('2019-06-14 15:15:00'),
            new DateTime('2019-06-14 20:15:00'),
            $basel
        );
        $team2Basel->addTeam($team2);

        $team1And3Lucerne = new TeamParticipation(
            new DateTime('2019-06-14 10:15:00'),
            new DateTime('2019-06-14 14:15:00'),
            $lucerne
        );
        $team1And3Lucerne->addTeam($team1);
        $team1And3Lucerne->addTeam($team3);

        $manager->persist($team2Basel);
        $manager->persist($team1And3Lucerne);

        $riehen = new Location('Riehen', '47.548154349374464', '7.68454967271316', $wave);
        $jugi = new Hotel(
            'Jugi Riehen',
            true,
            null,
            'Es hat 10 Ladestationen am Bahnhof',
            $riehen
        );

        $baselTroisRois = new Location('Trois Rois', '47.560441018678915', '7.5876688957214355', $wave);
        $troisRois = new Hotel(
            'Trois Rois',
            true,
            null,
            'Ladestationen verfügbar',
            $baselTroisRois
        );
        $jugiLodging = new Lodging(null, $jugi);
        $jugiLodging->addUser($andy);
        $jugiLodging->addUser($edy);
        $jugiLodging->addUser($jean);
        $jugiLodging->addUser($esther);
        $jugiLodging->addUser($user);

        $troisRoisLodging = new Lodging('Bitte anständige Kleidung (Business Casual) anziehen', $troisRois);
        $troisRoisLodging->addUser($lorenz);
        $troisRoisLodging->addUser($remo);

        $manager->persist($riehen);
        $manager->persist($jugi);
        $manager->persist($baselTroisRois);
        $manager->persist($troisRois);
        $manager->persist($jugiLodging);
        $manager->persist($troisRoisLodging);

        $manager->flush();
    }
}
