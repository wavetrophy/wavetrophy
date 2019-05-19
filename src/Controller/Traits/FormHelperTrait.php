<?php

namespace App\Controller\Traits;

use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;

/**
 * Class FormHelperTrait
 */
trait FormHelperTrait
{
    /**
     * @param FormInterface $form
     * @param $data
     * @param $key
     * @param $message
     */
    protected function notEmpty(FormInterface $form, $data, $key, $message)
    {
        if (!isset($data[$key]) || empty($data[$key])) {
            $form->addError(new FormError($message));
        }
    }
}
