<?php

namespace App\Form\EventActivity;

use App\Entity\EventActivity;
use App\Form\EventActivity\EventActivity as EventActivityAliasConstraint;
use App\Repository\EventActivityRepository;
use Psr\Container\ContainerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class EventActivityType
 */
class EventActivityType extends AbstractType
{
    /**
     * @var Request
     */
    private $request;

    /**
     * @var EventActivityRepository
     */
    private $eventActivityRepository;

    public function __construct(EventActivityRepository $activityRepository, ContainerInterface $container)
    {
        $this->eventActivityRepository = $activityRepository;
        $this->request = $container->get('request_stack')->getCurrentRequest();
    }

    public function getParent()
    {
        return HiddenType::class;
    }

    public function getBlockPrefix()
    {
        return 'event_activities';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'event_activities' => null,
            'data_class' => null,
            'inherit_data' => true,
            'required' => true,
            'compound' => true,
            'constraints' => [new EventActivityAliasConstraint(['name' => 'activities'])]
        ])->setRequired(['event_activities']);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);
        if (array_key_exists('data', $options) && !empty($options['data'])) {
            $options['event_activities'] = $options['data'];
        }
        $data = null;
        if ($form->isSubmitted()) {
            $formData = $this->request->request->all();
            if (array_key_exists('activities', $formData)) {
                $data = $formData['activities'];
            }
        }
        $view->vars['event_activities'] = $options['event_activities'] ?? $data ?? null;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'wave',
            HiddenType::class,
            [
                'attr' => [''],
                'csrf_protection' => true,
                'inherit_data' => true,
                'required' => true,
            ]
        );
    }
}
