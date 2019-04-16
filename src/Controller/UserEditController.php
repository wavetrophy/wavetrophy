<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserEmail;
use App\Entity\UserPhonenumber;
use Doctrine\ORM\EntityRepository;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilder;

/**
 * Class UserEditController
 */
class UserEditController extends EasyAdminController
{
    /**
     * @param User $user
     * @param string $view
     *
     * @return FormBuilder
     */
    protected function createEntityFormBuilder($user, $view): FormBuilder
    {
        $formBuilder = parent::createEntityFormBuilder($user, $view);
        if ($this->request->query->get('action') === 'edit') {
            $formBuilder->add(
                'emails',
                EntityType::class,
                [
                    'class' => UserEmail::class,
                    'required' => false,
                    // same as in \EasyCorp\Bundle\EasyAdminBundle\Form\Type\Configurator\EntityTypeConfigurator::configure
                    'multiple' => true,
                    'attr' => [
                        // same as in \EasyCorp\Bundle\EasyAdminBundle\Form\Type\Configurator\EntityTypeConfigurator::configure
                        'data-widget' => 'select2',
                    ],
                    // display profession only from same organization
                    'query_builder' => function (EntityRepository $repository) use ($user) {
                        $query = $repository->createQueryBuilder('ue')
                            ->where('ue.user = :user')
                            ->where('ue.isPublic = true')
                            ->setParameter('user', $user);
                        return $query;
                    },
                ]
            );
            $formBuilder->add(
                'phonenumbers',
                EntityType::class,
                [
                    'class' => UserPhonenumber::class,
                    'required' => false,
                    // same as in \EasyCorp\Bundle\EasyAdminBundle\Form\Type\Configurator\EntityTypeConfigurator::configure
                    'multiple' => true,
                    'attr' => [
                        // same as in \EasyCorp\Bundle\EasyAdminBundle\Form\Type\Configurator\EntityTypeConfigurator::configure
                        'data-widget' => 'select2',
                    ],
                    // display phonenumber only from same organization
                    'query_builder' => function (EntityRepository $repository) use ($user) {
                        $query = $repository->createQueryBuilder('up')
                            ->where('up.user = :user')
                            ->where('up.isPublic = true')
                            ->setParameter('user', $user);
                        return $query;
                    },
                ]
            );
        }

        return $formBuilder;
    }
}
