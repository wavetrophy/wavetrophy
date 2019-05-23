<?php

namespace App\Form;

use App\Form\Coordinate\Coordinate;
use App\Repository\EventActivityRepository;
use Psr\Container\ContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CoordinationType
 */
class CoordinateType extends AbstractType
{
    /**
     * @var Request
     */
    private $request;

    public function __construct(ContainerInterface $container)
    {
        $this->request = $container->get('request_stack')->getCurrentRequest();
    }

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
        if (array_key_exists('data', $options) && !empty($options['data'])) {
            $options['location'] = $options['data'];
        }
        $data = null;
        if ($form->isSubmitted()) {
            $formData = $this->request->request->all();
            if (array_key_exists('location', $formData)) {
                $data = $formData['location'];
            }
        }
        $view->vars['location'] = $options['location'] ?? $data ?? null;
    }

    public function getBlockPrefix()
    {
        return 'location';
    }
}
