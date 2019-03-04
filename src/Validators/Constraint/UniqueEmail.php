<?php

namespace App\Validators\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * Class UniqueEmail
 *
 * @Annotation
 */
class UniqueEmail extends Constraint
{
    public $message;

    /**
     * UniqueEmail constructor.
     *
     * @param array|mixed $options
     */
    public function __construct($options)
    {
        parent::__construct($options);
        $this->message = 'User already registered.';
    }
}
