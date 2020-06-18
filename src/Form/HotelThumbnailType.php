<?php

namespace App\Form;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

/**
 * Class HotelThumbnailType
 */
class HotelThumbnailType extends VichImageType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);
        // $resolver->setDefaults(['required' => false]);
        $resolver->setRequired('hotel');
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        parent::buildView($view, $form, $options);
        $object = $options['hotel'];
        if (null !== $object) {
            if (!$options['imagine_pattern']) {
                $view->vars['image_uri'] = $this->resolveUriOption($options['image_uri'], $object, $form);
            }

            $view->vars = \array_replace(
                $view->vars,
                $this->resolveDownloadLabel($options['download_label'], $object, $form)
            );

            $view->vars['download_uri'] = $this->resolveUriOption($options['download_uri'], $object, $form);
        }
        $view->vars['object'] = $object;
    }
}
