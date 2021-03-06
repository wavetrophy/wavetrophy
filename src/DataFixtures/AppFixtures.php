<?php

namespace App\DataFixtures;

use App\Entity\Answer;
use App\Entity\Event;
use App\Entity\EventParticipation;
use App\Entity\Group;
use App\Entity\Hotel;
use App\Entity\Lodging;
use App\Entity\Question;
use App\Entity\Team;
use App\Entity\User;
use App\Entity\UserEmail;
use App\Entity\UserPhonenumber;
use App\Entity\Wave;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Lexik\Bundle\JWTAuthenticationBundle\Security\Authentication\Token\JWTUserToken;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class AppFixtures extends Fixture implements FixtureGroupInterface
{
    private $storage;

    public function __construct(TokenStorageInterface $storage)
    {
        $this->storage = $storage;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setFirstName("Björn");
        $user->setLastName("Pfoster");
        $user->setUsername("bjoern");
        $user->setEmail("bjoern.pfoster@example.com");
        $manager->persist($user);
        $user->setPlainPassword("bjoern");
        $user->setHasReceivedWelcomeEmail(true);
        $user->addRole('ROLE_ADMIN');
        $token = new JWTUserToken(['ROLE_ADMIN'], $user);
        $this->storage->setToken($token);
        $user->setCreatedBy($user);

        $manager->flush();

        $user->setPlainPassword("bjoern");
        $user->setEnabled(true);
        $user->setSuperAdmin(true);

        $wave = new Wave();
        $wave->setName('WAVE Switzerland');
        $wave->setCountry('Switzerland');
        $wave->setStart(new DateTime('2019-06-14 10:00:00'));
        $wave->setEnd(new DateTime('2019-06-22 18:00:00'));
        $wave->setCreatedBy($user);

        $manager->persist($user);
        $manager->persist($wave);

        $lucerneGreeting = new Event(
            'Begrüssung',
            'Die Begrüssung im Theater',
            $wave,
            new DateTime('2019-06-14 10:00:00'),
            new DateTime('2019-06-14 10:45:00'),
            '47.03892207982464',
            '8.318022908459511'
        );
        $lucerneGreeting->setCreatedBy($user);
        $lucerneShow = new Event(
            'Auto Show',
            'Eine Auto Show aller Autos der WAVE',
            $wave,
            new DateTime('2019-06-14 11:00:00'),
            new DateTime('2019-06-14 11:45:00'),
            '47.03892207982464',
            '8.318022908459511'
        );
        $lucerneShow->setCreatedBy($user);
        $lucerneLunch = new Event(
            'Lunch',
            null,
            $wave,
            new DateTime('2019-06-14 12:00:00'),
            new DateTime('2019-06-14 13:00:00'),
            '47.03892207982464',
            '8.318022908459511'
        );
        $lucerneLunch->setCreatedBy($user);
        $lucerneDeparture = new Event(
            'Abfahrt',
            'Abfahrt aller Autos im 2 Minuten Rhytmus',
            $wave,
            new DateTime('2019-06-14 14:00:00'),
            new DateTime('2019-06-14 14:30:00'),
            '47.03892207982464',
            '8.318022908459511'
        );
        $lucerneDeparture->setCreatedBy($user);

        $baselGreeting = new Event(
            'Begrüssung',
            'Die Begrüssung im Dreispitz',
            $wave,
            new DateTime('2019-06-14 15:00:00'),
            new DateTime('2019-06-14 15:45:00'),
            '47.529612457152716',
            '7.583999021162867'
        );
        $baselGreeting->setCreatedBy($user);
        $baselShow = new Event(
            'Führung',
            'Die Führung durch das Dreispitzareal',
            $wave,
            new DateTime('2019-06-14 16:00:00'),
            new DateTime('2019-06-14 16:45:00'),
            '47.529612457152716',
            '7.583999021162867'
        );
        $baselShow->setCreatedBy($user);
        $baselLunch = new Event(
            'Abendessen',
            null,
            $wave,
            new DateTime('2019-06-14 18:00:00'),
            new DateTime('2019-06-14 19:00:00'),
            '47.529612457152716',
            '7.583999021162867'
        );
        $baselLunch->setCreatedBy($user);
        $baselDeparture = new Event(
            'Abfahrt',
            'Abfahrt aller Autos im 2 Minuten Rhytmus',
            $wave,
            new DateTime('2019-06-14 20:00:00'),
            new DateTime('2019-06-14 20:30:00'),
            '47.529612457152716',
            '7.583999021162867'
        );
        $baselDeparture->setCreatedBy($user);

        $manager->persist($lucerneGreeting);
        $manager->persist($lucerneShow);
        $manager->persist($lucerneLunch);
        $manager->persist($lucerneDeparture);
        $manager->persist($baselGreeting);
        $manager->persist($baselShow);
        $manager->persist($baselLunch);
        $manager->persist($baselDeparture);

        $group1 = new Group('Gruppe 1', $wave);
        $group1->setCreatedBy($user);
        $team1 = new Team('Renault', 1, $group1);
        $team1->setCreatedBy($user);
        $lorenz = new User('bjoern@pfoster.ch', 'Lorenz', 'Camenisch', $team1);
        $lorenz->setCreatedBy($user);
        $remo = new User('remo@example.com', 'Remo', 'Camenisch', $team1);
        $remo->setCreatedBy($user);
        $remo->setHasReceivedWelcomeEmail(true);
        $remoMail1 = new UserEmail('remo1@cc.cc', true, true, null, $remo);
        $remoMail2 = new UserEmail('remo2@cc.cc', true, true, null, $remo);
        $remoMail3 = new UserEmail('remo3@cc.cc', true, true, null, $remo);
        $remoMail4 = new UserEmail('remo4@cc.cc', true, true, null, $remo);
        $remoMail5 = new UserEmail('remo5@cc.cc', true, true, null, $remo);

        $manager->persist($remoMail1);
        $manager->persist($remoMail2);
        $manager->persist($remoMail3);
        $manager->persist($remoMail4);
        $manager->persist($remoMail5);

        $remoPhone1 = new UserPhonenumber('761234567', '+41', true, $remo);
        $remoPhone2 = new UserPhonenumber('762234567', '+41', true, $remo);
        $remoPhone3 = new UserPhonenumber('763234567', '+41', true, $remo);
        $remoPhone4 = new UserPhonenumber('764234567', '+41', true, $remo);
        $remoPhone5 = new UserPhonenumber('765234567', '+41', true, $remo);

        $manager->persist($remoPhone1);
        $manager->persist($remoPhone2);
        $manager->persist($remoPhone3);
        $manager->persist($remoPhone4);
        $manager->persist($remoPhone5);

        $team2 = new Team('Pilatus', 2, $group1);
        $team2->setCreatedBy($user);
        $andy = new User('andy@example.com', 'Andy', 'Alig', $team2);
        $andy->setCreatedBy($user);
        $andy->setHasReceivedWelcomeEmail(true);
        $edy = new User('edy@example.com', 'Edy', 'Künzli', $team2);
        $edy->setHasReceivedWelcomeEmail(true);
        $edy->setCreatedBy($user);

        $group2 = new Group('Gruppe 2', $wave);
        $group2->setCreatedBy($user);
        $team3 = new Team('Jura Energie', 3, $group2);
        $team3->setCreatedBy($user);
        $jean = new User('jean@example.com', 'Jean', 'Oppliger', $team3);
        $jean->setHasReceivedWelcomeEmail(true);
        $jean->setCreatedBy($user);
        $esther = new User('esther@example.com', 'Esther', 'Oppliger', $team3);
        $esther->setHasReceivedWelcomeEmail(true);
        $esther->setCreatedBy($user);

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

        $team2Basel = new EventParticipation(
            new DateTime('2019-06-14 15:15:00'),
            new DateTime('2019-06-14 20:15:00'),
            $baselGreeting
        );
        $team2Basel->setCreatedBy($user);
        $team2Basel->addTeam($team2);

        $team1And3Lucerne = new EventParticipation(
            new DateTime('2019-06-14 10:15:00'),
            new DateTime('2019-06-14 14:15:00'),
            $lucerneGreeting
        );
        $team1And3Lucerne->setCreatedBy($user);
        $team1And3Lucerne->addTeam($team1);
        $team1And3Lucerne->addTeam($team3);

        $manager->persist($team2Basel);
        $manager->persist($team1And3Lucerne);

        $jugi = new Hotel(
            $wave,
            new File(__DIR__ . '/fixture.svg'),
            'Jugi Riehen',
            true,
            null,
            null,
            '49.529612457152716',
            '9.583999021162867'
        );
        $jugi->setCheckIn(new DateTime('2019-06-17 21:00:00'));
        $jugi->setCheckOut(new DateTime('2019-06-18 09:00:00'));
        $jugi->setCreatedBy($user);

        $troisRois = new Hotel(
            $wave,
            new File(__DIR__ . '/fixture.svg'),
            'Trois Rois',
            true,
            null,
            null,
            '47.529612457152716',
            '7.583999021162867'
        );
        $troisRois->setCheckIn(new DateTime('2019-06-15 21:00:00'));
        $troisRois->setCheckOut(new DateTime('2019-06-16 09:00:00'));
        $troisRois->setCreatedBy($user);
        $jugiLodging = new Lodging(null, $jugi);
        $jugiLodging->setCreatedBy($user);
        $jugiLodging->setHotel($jugi);
        $jugiLodging->addUser($andy);
        $jugiLodging->addUser($edy);
        $jugiLodging->addUser($jean);
        $jugiLodging->addUser($esther);
        $jugiLodging->addUser($user);

        $troisRoisLodging = new Lodging('Bitte anständige Kleidung (Business Casual) anziehen', $troisRois);
        $troisRoisLodging->setCreatedBy($user);
        $troisRoisLodging->setHotel($troisRois);
        $troisRoisLodging->addUser($lorenz);
        $troisRoisLodging->addUser($remo);

        $manager->persist($jugi);
        $manager->persist($troisRois);
        $manager->persist($jugiLodging);
        $manager->persist($troisRoisLodging);

        $question1 = new Question('Where to park in lucerne', 'I just arrived in lucerne. Where do i park?', $group1,
            $lorenz);
        $question1->setResolved(true);
        $question1->setCreatedBy($lorenz);
        $answer1 = new Answer('At the train station near the center. There are some chargers near the exit. But its very costly',
            true, $question1);
        $answer1->setCreatedBy($remo);
        $answer2 = new Answer('Somewhere', false, $question1);
        $answer2->setCreatedBy($andy);


        $question2 = new Question('Where to park in basel', 'I just arrived in basel. Where do i park?', $group1,
            $lorenz);
        $question2->setCreatedBy($lorenz);
        $answer3 = new Answer('At the train station near the center. There are some chargers near the exit. But its very costly',
            false, $question2);
        $answer3->setCreatedBy($remo);
        $answer4 = new Answer('Somewhere else', false, $question2);
        $answer4->setCreatedBy($andy);

        $manager->persist($question1);
        $manager->persist($question2);
        $manager->persist($answer1);
        $manager->persist($answer2);
        $manager->persist($answer3);
        $manager->persist($answer4);

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['dev'];
    }
}
