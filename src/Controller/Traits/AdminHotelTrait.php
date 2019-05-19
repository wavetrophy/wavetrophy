<?php

namespace App\Controller\Traits;

use App\Entity\Hotel;
use App\Entity\Lodging;
use App\Entity\Team;
use App\Entity\Wave;
use App\Form\CoordinateType;
use App\Form\FormType;
use App\Form\HotelTeamType;
use App\Form\HotelThumbnailType;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;

/**
 * Trait AdminHotelTrait
 *
 * @property EntityManager $em
 */
trait AdminHotelTrait
{
    protected function createHotelNewForm(Hotel $entity, $fields)
    {
        $formOptions = $this->entity['new']['form_options'];
        $formOptions['entity'] = $this->entity['name'];
        $formOptions['view'] = 'new';

        /** @var FormBuilderInterface $formBuilder */
        $formBuilder = $this->get('form.factory')
            ->createNamedBuilder(
                $entity,
                FormType::class,
                null,
                $formOptions
            );
        $formBuilder->add('thumbnailImage', VichImageType::class, ['required' => true]);
        $formBuilder->add('name', TextType::class, ['required' => true]);
        $formBuilder->add('checkIn', DateTimeType::class, ['required' => true]);
        $formBuilder->add('checkOut', DateTimeType::class, ['required' => true,]);
        $formBuilder->add('location', CoordinateType::class, ['required' => true]);
        $formBuilder->add('teams', HotelTeamType::class);
        $form = $formBuilder->getForm();
        return $form;
    }

    protected function createHotelEditForm(Hotel $entity, $fields)
    {
        $formOptions = $this->entity['new']['form_options'];
        $formOptions['entity'] = $this->entity['name'];
        $formOptions['view'] = 'new';

        /** @var FormFactoryInterface $factory */
        $factory = $this->get('form.factory');
        $formBuilder = $factory->createNamedBuilder(
            $this->entity['name'],
            FormType::class,
            $entity,
            $formOptions
        );

        $teams = $this->em->getRepository(Hotel::class)->getTeamsDataForHotel($entity);

        $formBuilder->add('thumbnailImage', HotelThumbnailType::class, ['hotel' => $entity,]);

        $formBuilder->add('name', TextType::class, ['required' => true]);
        $formBuilder->add('checkIn', DateTimeType::class, ['required' => true]);
        $formBuilder->add('checkOut', DateTimeType::class, ['required' => true]);
        $formBuilder->add('location', CoordinateType::class, ['required' => true, 'data' => $entity->getLocation()]);
        $formBuilder->add('teams', HotelTeamType::class, ['data' => $teams]);
        $form = $formBuilder->getForm();
        return $form;
    }

    protected function persistHotelEntity(Hotel $hotel, FormInterface $form)
    {
        $data = $this->request->request->all();
        $wave = $this->em->getRepository(Wave::class)->find($data['teams']['wave']);
        $this->saveHotel($hotel, $form, $wave, $data);

        if (array_key_exists('same-for-all', $data) && $data['same-for-all'] === 'on') {
            $teams = $this->em->getRepository(Team::class)->getWaveTeams($wave->getId());
            foreach ($teams as $team) {
                $this->createLodging($hotel, $team, $data['comment'] ?? null);
            }
        } else {
            $teams = $data['team'];
            foreach ($teams as $id => $teamData) {
                if (array_key_exists('enabled', $teamData) && $teamData['enabled'] === 'on') {
                    $team = $this->em->getRepository(Team::class)->find($id);
                    $this->createLodging($hotel, $team, $teamData['comment'] ?? null);
                }
            }
        }

        $this->em->flush();
    }

    protected function updateHotelEntity(Hotel $hotel, FormInterface $form)
    {
        $data = $this->request->request->all();
        $wave = $this->em->getRepository(Wave::class)->find($data['Hotel']['teams']['wave']);
        $this->saveHotel($hotel, $form, $wave, $data);

        if (array_key_exists('same-for-all', $data) && $data['same-for-all'] === 'on') {
            $teams = $this->em->getRepository(Team::class)->getWaveTeams($wave->getId());
            foreach ($teams as $team) {
                foreach ($team->getUsers() as $user) {
                    $comment = $data['comment'] ?? null;
                    $this->saveLodging($hotel, $user, $comment);
                }
            }
        } else {
            $teams = $data['team'];
            foreach ($teams as $id => $teamData) {
                $team = $this->em->getRepository(Team::class)->find($id);
                if (array_key_exists('enabled', $teamData) && $teamData['enabled'] === 'on') {
                    foreach ($team->getUsers() as $user) {
                        $comment = $teamData['comment'] ?? null;
                        $this->saveLodging($hotel, $user, $comment);
                    }
                } else {
                    $this->deleteLodgings($hotel, $team);
                }
            }
        }

        $this->em->flush();
    }

    /**
     * @param Hotel $hotel
     * @param FormInterface $form
     * @param Wave $wave
     * @param array $data
     */
    protected function saveHotel(Hotel $hotel, FormInterface $form, Wave $wave, array $data)
    {
        $hotelData = $form->getData();
        $hotel->setName($hotelData->getName());
        $hotel->setCheckIn($hotelData->getCheckIn());
        $hotel->setCheckOut($hotelData->getCheckOut());
        $hotel->setThumbnailImage($hotelData->getThumbnailImage());
        $hotel->setBreakfastIncluded(true);
        $hotel->setLocation($data['location']);
        $hotel->setWave($wave);
        $this->em->persist($hotel);
    }

    /**
     * @param Hotel $hotel
     * @param Team $team
     * @param $comment
     */
    protected function createLodging(Hotel $hotel, Team $team, $comment): void
    {
        $lodging = new Lodging();
        foreach ($team->getUsers() as $user) {
            $lodging->addUser($user);
        }
        $lodging->setHotel($hotel);

        if (!empty($comment)) {
            $lodging->setComment($comment);
        }

        $this->em->persist($lodging);
    }

    /**
     * @param Hotel $hotel
     * @param $user
     * @param $comment
     */
    protected function saveLodging(Hotel $hotel, $user, $comment): void
    {
        $lodgings = $this->em->getRepository(Lodging::class)->findLodging($hotel, $user);

        if (!$lodgings || !isset($lodgings[0])) {
            $lodging = new Lodging();
            $lodging->setHotel($hotel);
            $lodging->addUser($user);
        } else {
            /**
             * @var int $key
             * @var Lodging $lodgingEntity
             */
            foreach ($lodgings as $key => $lodgingEntity) {
                if ($key === 0) {
                    continue;
                }

                // Delete all old entities except the first one
                $lodgingEntity->setDeletedAt(new \DateTime());
                $this->em->persist($lodgingEntity);
            }

            /** @var Lodging $lodging */
            $lodging = $lodgings[0];
        }

        if (isset($comment)) {
            $lodging->setComment($comment);
        }

        $this->em->persist($lodging);
    }

    /**
     * @param Hotel $hotel
     * @param Team|null $team
     */
    protected function deleteLodgings(Hotel $hotel, ?Team $team)
    {
        foreach ($team->getUsers() as $user) {
            $lodgings = $this->em->getRepository(Lodging::class)->findLodging($hotel, $user);
            foreach ($lodgings as $lodging) {
                $lodging->setDeletedAt(new \DateTime());
            }
        }
    }
}
