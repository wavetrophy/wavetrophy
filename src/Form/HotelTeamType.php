<?php

namespace App\Form;

use App\Entity\Wave;
use App\Repository\TeamRespository;
use App\Repository\WaveRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class TeamTimeType
 */
class HotelTeamType extends AbstractType
{
    private $waveRepository;

    public function __construct(WaveRepository $waveRepository)
    {
        $this->waveRepository = $waveRepository;
    }

    public function getParent()
    {
        return HiddenType::class;
    }

    public function getBlockPrefix()
    {
        return 'hotel_teams';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'teams' => null,
            'data_class' => null,
            'inherit_data' => true,
            'required' => true,
            'compound' => true,
        ])->setRequired(['teams']);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);
        if (array_key_exists('data', $options) && !empty($options['data'])) {
            $options['teams'] = $options['data'];
        }
        $view->vars['teams'] = $options['teams'] ?? null;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'wave',
            EntityType::class,
            [
                'data_class' => Wave::class,
                'class' => Wave::class,
                'attr' => [''],
                'csrf_protection' => true,
                'inherit_data' => true,
                'empty_data' => function (FormInterface $form) {
                    return $this->waveRepository->getCurrentWave();
                },
            ]
        );
    }
}
