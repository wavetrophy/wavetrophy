<?php

namespace App\Command;

use App\Entity\Event;
use App\Entity\EventParticipation;
use App\Entity\Team;
use App\Entity\Wave;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class GenerateParticipationCommand extends Command
{
    protected static $defaultName = 'generate:participation';
    /**
     * @var RegistryInterface
     */
    private $registry;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct('generate:participation');
        $this->registry = $registry;
    }

    protected function configure()
    {
        $this->setDescription('Generates a participation to every event of a wave for a user')
            ->addOption('wave', 'w', InputOption::VALUE_OPTIONAL, 'The wave to add the event participations to')
            ->addOption('team', 't', InputOption::VALUE_REQUIRED, 'The team to add the event participations to');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $waveId = (int)$input->getOption('wave');
        $waveRepository = $this->registry->getRepository(Wave::class);
        $wave = $waveRepository->getCurrentWave();

        $teamId = (int)$input->getOption('team');
        $team = $this->registry->getRepository(Team::class)->find($teamId);

        if (empty($team)) {
            $io->error('Team not found');

            return;
        }

        if ($waveId) {
            /** @var Wave $wave */
            $wave = $waveRepository->find($waveId);
            if ($wave) {
                $io->note('Generating a participation for every event at ' . $wave->getName());
            } else {
                $io->warning('WAVETROPHY Not found. Using current WAVETROPHY');
            }
        } else {
            $io->warning('WAVETROPHY Not found. Using current WAVETROPHY');
        }

        /** @var Event $event */
        foreach ($wave->getEvents() as $event) {
            $eventParticipation = $this->registry->getRepository(EventParticipation::class)
                ->findEventParticipations($event, $team);

            if ($eventParticipation) {
                $io->error('Team already participating at ' . $event->getName() . '(' . $event->getId() . ')');
                continue;
            }
            /** @var EventParticipation $first */
            $first = $event->getParticipations()->first();
            if (!$first instanceof EventParticipation) {
                $io->error('Event (' . $event->getId() . ') does not have any participations that can be used as reference for the arrival and departure');
                continue;
            }

            $participation = new EventParticipation($first->getArrival(), $first->getDeparture(), $event);
            $participation->addTeam($team);

            $event->addParticipation($participation);

            $io->success('Team participating at event (' . $event->getId() . ')');

            $this->registry->getEntityManager()->persist($participation);
            $this->registry->getEntityManager()->persist($event);
        }

        $this->registry->getEntityManager()->flush();

        $io->success('Successfully generated participations.');
    }
}
