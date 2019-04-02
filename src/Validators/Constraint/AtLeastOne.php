<?php

namespace App\Validators\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * Class AtLeastOne
 *
 * @Annotation
 */
class AtLeastOne extends Constraint
{
    public $message;

    /**
     * AtLeastOne constructor.
     *
     * @param array|mixed $options
     */
    public function __construct($options)
    {
        parent::__construct($options);
        $this->message = 'Needs to have at lease one element';
    }

    public function validatedBy()
    {
        return AtLeastOneValidator::class;
    }
}
