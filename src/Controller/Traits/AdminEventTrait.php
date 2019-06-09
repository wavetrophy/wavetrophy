<?php

namespace App\Controller\Traits;

use App\Entity\Event;
use App\Entity\EventActivity;
use App\Entity\EventParticipation;
use App\Entity\Lodging;
use App\Entity\Team;
use App\Entity\Wave;
use App\Form\CoordinateType;
use App\Form\EventActivity\EventActivityType;
use App\Form\EventTeamType;
use App\Form\EventThumbnailType;
use App\Form\FormType;
use DateTime;
use DateTimeZone;
use Doctrine\ORM\EntityManager;
use InvalidArgumentException;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;

/**
 * Class AdminEventTrait
 *
 * @property EntityManager $em
 */
trait AdminEventTrait
{
    /**
     * EasyAdmin create event new form hook.
     *
     * @param Event $entity
     * @param $fields
     *
     * @return FormInterface
     */
    protected function createEventNewForm(Event $entity, $fields)
    {
        $data = $this->request->request->all();
        $formOptions = $this->entity['new']['form_options'];
        $formOptions['entity'] = $this->entity['name'];
        $formOptions['view'] = 'new';

        $eventActivities = $this->em->getRepository(EventActivity::class)->findAllForEvent($entity);
        $activities = $this->getActivities($data, $eventActivities);

        /** @var FormBuilderInterface $formBuilder */
        $formBuilder = $this->get('form.factory')
            ->createNamedBuilder(
                $entity,
                FormType::class,
                null,
                $formOptions
            );

        $years = [];
        $currentYear = (int)(new DateTime())->format('Y');
        for ($i = 0; $i < 5; $i++) {
            $years[] = $currentYear + $i;
        }

        $formBuilder->add('thumbnailImage', VichImageType::class, ['required' => false]);
        $formBuilder->add('name', TextType::class, ['required' => true]);
        $formBuilder->add('start', DateType::class,
            ['required' => true, 'attr' => ['data-start' => ''], 'years' => $years]);
        $formBuilder->add('end', DateType::class,
            ['required' => true, 'attr' => ['data-end' => ''], 'years' => $years]);
        $formBuilder->add('event_activites', EventActivityType::class, ['event_activities' => $activities]);

        $formBuilder->add('location', CoordinateType::class,
            ['required' => true, 'data' => $entity->getLocation() ?: null]);
        $formBuilder->add('teams', EventTeamType::class);
        $form = $formBuilder->getForm();
        return $form;
    }

    /**
     * EasyAdmin create event edit form hook
     *
     * @param Event $entity
     * @param $fields
     *
     * @return FormInterface
     */
    protected function createEventEditForm(Event $entity, $fields)
    {
        $data = $this->request->request->all();
        $formOptions = $this->entity['new']['form_options'];
        $formOptions['entity'] = $this->entity['name'];
        $formOptions['view'] = 'new';

        $teams = $this->em->getRepository(Event::class)->getTeamsDataForEvent($entity);
        $eventActivities = $this->em->getRepository(EventActivity::class)->findAllForEvent($entity);
        $activities = $this->getActivities($data, $eventActivities);

        /** @var FormBuilderInterface $formBuilder */
        $formBuilder = $this->get('form.factory')
            ->createNamedBuilder(
                $this->entity['name'],
                FormType::class,
                null,
                $formOptions
            );
        $formBuilder->add('thumbnailImage', EventThumbnailType::class,
            ['event' => $entity, 'required' => false, 'allow_delete' => true]);
        $formBuilder->add('name', TextType::class, ['required' => true, 'data' => $entity->getName()]);

        $years = [];
        $currentYear = (int)(new DateTime())->format('Y');
        for ($i = 0; $i < 5; $i++) {
            $years[] = $currentYear + $i;
        }

        $formBuilder->add(
            'start',
            DateType::class,
            ['required' => true, 'attr' => ['data-start' => ''], 'data' => $entity->getStart(), 'years' => $years]
        );
        $formBuilder->add(
            'end',
            DateType::class,
            ['required' => true, 'attr' => ['data-end' => ''], 'data' => $entity->getEnd(), 'years' => $years]
        );
        $formBuilder->add(
            'event_activities',
            EventActivityType::class,
            ['required' => true, 'event_activities' => $activities]
        );
        $formBuilder->add('location', CoordinateType::class,
            ['required' => true, 'data' => $entity->getLocation() ?: null]);
        $formBuilder->add('teams', EventTeamType::class, ['teams' => $teams]);
        $form = $formBuilder->getForm();

        return $form;
    }

    /**
     * EasyAdmin persist event entity hook
     *
     * @param Event $event
     * @param FormInterface $form
     */
    protected function persistEventEntity(Event $event, FormInterface $form)
    {
        $data = $this->request->request->all();
        $wave = $this->em->getRepository(Wave::class)->find($data['teams']['wave']);
        $this->saveEvent($event, $form, $wave, $data);

        $sameForAll = $this->getSameForAll($data);

        $arrival = $this->getArrival($event, $sameForAll, $data);
        $departure = $this->getDeparture($event, $sameForAll, $data);

        if (!array_key_exists('activities', $data)) {
            throw new InvalidArgumentException('At least one Activity must be set.');
        }

        $activities = $data['activities'];

        foreach ($activities as $key => $activity) {
            // new ids will also contain "new" (see frontend JS code)
            $eventActivity = new EventActivity();
            $this->saveEventActivity($event, $eventActivity, $activity, $sameForAll, $data);
        }

        if ($sameForAll) {
            $teams = $this->em->getRepository(Team::class)->getWaveTeams($wave->getId());
            $this->createEventParticipation($event, $teams, $arrival, $departure);
        } else {
            $teams = $data['team'];
            foreach ($teams as $id => $teamData) {
                if (array_key_exists('enabled', $teamData) && $teamData['enabled'] === 'on') {
                    $teamArrival = $this->getTeamArrival($teamData, $arrival);
                    $teamDeparture = $this->getTeamDeparture($teamData, $departure);

                    $team = $this->em->getRepository(Team::class)->find($id);
                    $this->createEventParticipation($event, [$team], $teamArrival, $teamDeparture);
                }
            }
        }

        $this->em->flush();
    }

    /**
     * EasyAdmin update event entity hook
     *
     * @param Event $event
     * @param FormInterface $form
     */
    protected function updateEventEntity(Event $event, FormInterface $form)
    {
        $data = $this->request->request->all();
        $wave = $this->em->getRepository(Wave::class)->find($data['Event']['teams']['wave']);
        $this->saveEvent($event, $form, $wave, $data);

        $sameForAll = $this->getSameForAll($data);

        $arrival = $this->getArrival($event, $sameForAll, $data);
        $departure = $this->getDeparture($event, $sameForAll, $data);

        if (!array_key_exists('activities', $data)) {
            throw new InvalidArgumentException('At least one Activity must be set.');
        }

        $activityEntities = $this->em->getRepository(EventActivity::class)->findAllForEvent($event);
        $activities = $data['activities'];
        foreach ($activityEntities as $key => $activity) {
            // filter out all activities
            if (isset($activities[$activity->getId()])) {
                unset($activityEntities[$key]);
            }
        }

        foreach ($activityEntities as $activity) {
            // delete all activities that are not set in the array from the form
            $activity->setDeletedAt(new DateTime());
            $this->em->persist($activity);
        }

        foreach ($activities as $key => $activity) {
            // new ids will also contain "new" (see frontend JS code)
            $id = isset($activity['id']) ? $activity['id'] : 'new';
            if (preg_match('/new/', $id)) {
                $eventActivity = new EventActivity();
            } else {
                $eventActivity = $this->em->getRepository(EventActivity::class)->find($id);
            }
            $this->saveEventActivity($event, $eventActivity, $activity, $sameForAll, $data);
        }

        if (array_key_exists('same-for-all', $data) && $data['same-for-all'] === 'on') {
            $teams = $this->em->getRepository(Team::class)->getWaveTeams($wave->getId());
            foreach ($teams as $team) {
                $this->saveEventParticipation($event, $team, $arrival, $departure);
            }
        } else {
            $teams = $data['team'];
            foreach ($teams as $id => $teamData) {
                $team = $this->em->getRepository(Team::class)->find($id);
                if (array_key_exists('enabled', $teamData) && $teamData['enabled'] === 'on') {
                    $teamArrival = $this->getTeamArrival($teamData, $arrival);
                    $teamDeparture = $this->getTeamDeparture($teamData, $departure);
                    $this->saveEventParticipation($event, $team, $teamArrival, $teamDeparture);
                } else {
                    $this->deleteEventParticipation($event, $team);
                }
            }
        }

        $this->em->flush();
    }

    /**
     * Save an event.
     *
     * @param Event $event
     * @param FormInterface $form
     * @param Wave $wave
     * @param array $data
     */
    protected function saveEvent(Event $event, FormInterface $form, Wave $wave, array $data)
    {
        $eventData = $form->getData();
        $event->setName($eventData->getName());
        // Set the arrival and departure to the value that all have (or 00:00)
        $event->setStart($this->getArrival($eventData, $this->getSameForAll($data), $data));
        $event->setEnd($this->getDeparture($eventData, $this->getSameForAll($data), $data));
        $event->setThumbnailImage($eventData->getThumbnailImage());
        $event->setLocation($data['location']);
        $event->setWave($wave);
        $event->setDescription($eventData->getDescription());
        $this->em->persist($event);
    }

    /**
     * Create a new event participation
     *
     * @param Event $event
     * @param Team[]|null $teams
     * @param DateTime|null $arrival
     * @param DateTime|null $departure
     */
    protected function createEventParticipation(
        ?Event $event,
        ?array $teams,
        ?DateTime $arrival,
        ?DateTime $departure
    ): void {
        foreach ($teams as $team) {
            // To prevent the bug:
            // if you create just one participation per event for all teams and then change one time for one team, the
            // changes wont get applied.
            // See email from louis at Fri, 7 Jun 2019, 23:47 "Abfahrzeiten in Glarus - kannst du mal probieren die enizugeben?"
            $eventParticipation = new EventParticipation();
            $eventParticipation->addTeam($team);
            $eventParticipation->setEvent($event);

            if (isset($arrival)) {
                $eventParticipation->setArrival($arrival);
            }

            if (isset($departure)) {
                $eventParticipation->setDeparture($departure);
            }

            $this->em->persist($eventParticipation);
            $this->em->flush($eventParticipation);
        }
    }

    /**
     * Save an event participation
     *
     * @param Event $event
     * @param Team $team
     * @param DateTime|null $arrival
     * @param DateTime|null $departure
     */
    protected function saveEventParticipation(Event $event, Team $team, ?DateTime $arrival, ?DateTime $departure): void
    {
        $eventParticipations = $this->em->getRepository(EventParticipation::class)
            ->findEventParticipations($event, $team);

        if (!$eventParticipations || !isset($eventParticipations[0])) {
            $eventParticipation = new EventParticipation();
            $eventParticipation->setEvent($event);
            $eventParticipation->setArrival($arrival);
            $eventParticipation->setDeparture($departure);
            $eventParticipation->addTeam($team);
        } else {
            /**
             * @var int $key
             * @var EventParticipation $eventParticipation
             */
            foreach ($eventParticipations as $key => $eventParticipation) {
                if ($key === 0) {
                    continue;
                }

                // Delete all old entities except the first one
                $eventParticipation->setDeletedAt(new \DateTime());
                $this->em->persist($eventParticipation);

                $this->em->flush($eventParticipation);
            }

            /** @var Lodging $eventParticipation */
            $eventParticipation = $eventParticipations[0];
        }

        if (isset($departure)) {
            $eventParticipation->setDeparture($departure);
        }

        if (isset($arrival)) {
            $eventParticipation->setArrival($arrival);
        }

        $this->em->persist($eventParticipation);

        $this->em->flush($eventParticipation);
    }

    /**
     * Delete a participation
     *
     * @param Event $event
     * @param Team $team
     */
    public function deleteEventParticipation(Event $event, Team $team)
    {
        $eventParticipations = $this->em->getRepository(EventParticipation::class)
            ->findEventParticipations($event, $team);
        foreach ($eventParticipations as $participation) {
            $participation->setDeletedAt(new DateTime('now', new DateTimeZone('europe/zurich')));
            $this->em->persist($participation);
        }
    }

    /**
     * Get the arrival as datetime
     *
     * @param Event $event
     * @param bool $sameForAll
     * @param $data
     *
     * @return DateTime|null
     */
    protected function getArrival(Event $event, bool $sameForAll, $data): ?DateTime
    {
        $arrival = $event->getStart();
        if ($sameForAll && isset($data['arrival']) && !empty($data['arrival'])) {
            $arrivalDate = $event->getStart()->format('Y-m-d');
            $arrivalDateTime = $arrivalDate . ' ' . $data['arrival'] . ':00';
            $arrival = new DateTime($arrivalDateTime, new DateTimeZone('europe/zurich'));
            $arrival->setTimezone(new DateTimeZone('Europe/Zurich'));
        } else {
            if (isset($data['activities'])) {
                // sort by start date to get the first activity's start time
                usort($data['activities'], function ($a, $b) {
                    return $a['start'] <=> $b['start'];
                });
                $firstActivity = reset($data['activities']);

                $arrivalDate = $event->getStart()->format('Y-m-d');
                $arrivalDateTime = $arrivalDate . ' ' . $firstActivity['start'] . ':00';
                $arrival = new DateTime($arrivalDateTime, new DateTimeZone('europe/zurich'));
                $arrival->setTimezone(new DateTimeZone('Europe/Zurich'));
            }
        }
        return $arrival;
    }

    /**
     * Get the departure as datetime
     *
     * @param Event $event
     * @param bool $sameForAll
     * @param $data
     *
     * @return DateTime|null
     */
    protected function getDeparture(Event $event, bool $sameForAll, $data): ?DateTime
    {
        $departure = $event->getEnd();
        if ($sameForAll && isset($data['departure']) && !empty($data['departure'])) {
            $departureDate = $event->getStart()->format('Y-m-d');
            $departureDateTime = $departureDate . ' ' . $data['departure'] . ':00';
            $departure = new DateTime($departureDateTime, new DateTimeZone('Europe/Zurich'));
            $departure->setTimezone(new DateTimeZone('Europe/Zurich'));
        } else {
            if (isset($data['activities'])) {
                // sort by end date to get the last activity's end time
                usort($data['activities'], function ($a, $b) {
                    return $a['end'] <=> $b['end'];
                });
                $last = end($data['activities']);
                $departureDate = $event->getStart()->format('Y-m-d');
                $departureDateTime = $departureDate . ' ' . $last['end'] . ':00';
                $departure = new DateTime($departureDateTime, new DateTimeZone('Europe/Zurich'));
                $departure->setTimezone(new DateTimeZone('Europe/Zurich'));
            }
        }
        return $departure;
    }

    /**
     * Check if the same for all flag is set within the form
     *
     * @param $data
     *
     * @return bool
     */
    protected function getSameForAll($data): bool
    {
        $sameForAll = array_key_exists('same-for-all', $data) && $data['same-for-all'] === 'on';
        return $sameForAll;
    }

    /**
     * Get the team arrival as datetime based on the event arrival
     *
     * @param $teamData
     * @param DateTime|null $arrival
     *
     * @return DateTime|null
     */
    protected function getTeamArrival($teamData, ?DateTime $arrival): ?DateTime
    {
        if (isset($teamData['arrival']) && !empty($teamData['arrival'])) {
            list($hour, $minute) = explode(':', $teamData['arrival'], 2);
            $arrival->setTime($hour, $minute);
        }
        return $arrival;
    }

    /**
     * Get the team departure datetime based on the event departure
     *
     * @param $teamData
     * @param DateTime|null $departure
     *
     * @return DateTime|null
     */
    protected function getTeamDeparture($teamData, ?DateTime $departure): ?DateTime
    {
        if (isset($teamData['departure']) && !empty($teamData['departure'])) {
            list($hour, $minute) = explode(':', $teamData['departure'], 2);
            $departure->setTime($hour, $minute);
        }
        return $departure;
    }

    /**
     * @param Event $event
     * @param EventActivity $eventActivity
     * @param $activity
     * @param bool $sameForAll
     * @param $data
     */
    protected function saveEventActivity(
        Event $event,
        EventActivity $eventActivity,
        $activity,
        bool $sameForAll,
        $data
    ): void {
        $eventActivity->setEvent($event);
        $eventActivity->setTitle($activity['title']);

        list($hour, $minute) = explode(':', $activity['start'], 2);
        $start = $this->getArrival($event, $sameForAll, $data)->setTime($hour, $minute);
        $eventActivity->setStart($start);

        list($hour, $minute) = explode(':', $activity['end'], 2);
        $end = $this->getDeparture($event, $sameForAll, $data)->setTime($hour, $minute);
        $eventActivity->setEnd($end);

        if (!empty($activity['description'])) {
            $eventActivity->setDescription($activity['description']);
        }

        $this->em->persist($eventActivity);
    }

    /**
     * @param $data
     * @param $eventActivities
     *
     * @return array|mixed
     */
    protected function getActivities($data, $eventActivities)
    {
        $activities = [];

        if (isset($data['activities'])) {
            $activities = $data['activities'];
        }

        foreach ($eventActivities as $activity) {
            $activities[$activity->getId()] = [
                'id' => $activity->getId(),
                'title' => $activity->getTitle(),
                'start' => $activity->getStart()->format('H:i'),
                'end' => $activity->getEnd()->format('H:i'),
                'description' => $activity->getDescription(),
            ];
        }
        usort($activities, function ($a, $b) {
            return $a['start'] <=> $b['start'];
        });
        return $activities;
    }
}
