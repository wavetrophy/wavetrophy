<?php

namespace App\Command;

use App\Entity\Group;
use App\Entity\Team;
use App\Entity\User;
use App\Entity\UserPhonenumber;
use App\Entity\Wave;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImportUsersCommand extends Command
{
    protected static $defaultName = 'import:users';
    /**
     * @var RegistryInterface
     */
    private $doctrine;

    public function __construct(RegistryInterface $doctrine)
    {
        parent::__construct();
        $this->doctrine = $doctrine;
    }

    protected function configure()
    {
        $this->setDescription('Imports users from a csv')
            ->addOption('file', 'f', InputOption::VALUE_REQUIRED, 'The csv file with the users data inside')
            ->addOption('wave', 'w', InputOption::VALUE_OPTIONAL, 'The wave to import the users to');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $file = $input->getOption('file');
        $waveId = (int)$input->getOption('wave');

        $existing = [];
        $notFoundGroups = [];

        if (empty($wave)) {
            $waveId = $this->doctrine->getRepository(Wave::class)->getCurrentWave()->getId();
        }

        if (!file_exists($file)) {
            $io->error('Provided file does not exist');
            return;
        }

        $io->comment('Importing users for wave nr. ' . $waveId);
        $io->newLine();

        $entityManager = $this->doctrine->getManagerForClass(User::class);
        $handle = fopen($file, 'rb');
        $io->newLine();
        while (($row = fgetcsv($handle, 0, ';', '"', '\\')) !== false) {
            $groupNr = (int)$row[0];
            $teamName = $row[1];
            $teamNr = (int)$row[2];
            $firstName = $row[3];
            $lastName = $row[4];
            $email = $row[5];
            $telephone = $row[6];
            $alreadyExists = true;
            $groupFound = true;
            $user = $this->doctrine->getRepository(User::class)->findUserByName($firstName, $lastName);
            if (empty($user)) {
                $user = $this->doctrine->getRepository(User::class)->findUserByEmailsOrUsername($email);
                if (empty($user)) {
                    $alreadyExists = false;
                    $user = new User($email, $firstName, $lastName);
                    $user->setMustResetPassword(true);
                    $user->setHasReceivedWelcomeEmail(false);
                    $user->setHasReceivedSetupAppEmail(false);
                    $user->setRoles(['ROLE_USER']);
                }
            }

            $team = $this->doctrine->getRepository(Team::class)->getTeamForWaveByStartNumber($waveId, $teamNr);
            if (empty($team)) {
                $team = new Team($teamName, $teamNr);
                $entityManager->persist($team);
                $group = $this->doctrine->getRepository(Group::class)->findByNameForWave($waveId, (string)$groupNr);
                if (!empty($group)) {
                    $team->setGroup($group);
                } else {
                    $groupFound = false;
                }
            }

            $user->setTeam($team);

            if (!empty($telephone)) {
                list($countryCode, $number) = explode(' ', $telephone, 2);
                $phoneNumber = new UserPhonenumber($number, $countryCode);
                $phoneNumber->setIsPublic(false);
                $entityManager->persist($phoneNumber);
                $user->addPhonenumber($phoneNumber);
            }

            $message = "{$user->getFirstName()} {$user->getLastName()} ({$user->getEmail()})";
            if (!$alreadyExists) {
                $io->writeln($message);
            } else {
                $existing[] = $message . ' already exists';
            }

            if (!$groupFound) {
                $notFoundGroups[] = 'Group ' . $groupNr . ' for user ' . $message . ' was not found';
            }

            $entityManager->persist($user);
        }
        fclose($handle);
        $entityManager->flush();

        if (!empty($existing)) {
            foreach ($existing as $person) {
                $io->warning($person);
            }
        }

        $io->newLine(2);

        if (!empty($notFoundGroups)) {
            foreach ($notFoundGroups as $notFoundGroup) {
                $io->warning($notFoundGroup);
            }
        }

        $io->newLine();
        $io->success('Finished importing users.');
    }
}
