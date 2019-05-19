<?php

namespace App\Form;

use App\Form\Coordinate\Coordinate;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CoordinationType
 */
class CoordinateType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
            'inherit_data' => true,
            'csrf_protection' => false,
            'block_name' => 'location',
            'required' => true,
            'constraints' => [new Coordinate(['name' => 'location'])],
        ]);
    }

    public function getParent()
    {
        return HiddenType::class;
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);

        $view->vars = array_merge($view->vars, [
            'location' => isset($options['data']) ? $options['data'] : null,
        ]);
    }

    public function getBlockPrefix()
    {
        return 'location';
    }
}
