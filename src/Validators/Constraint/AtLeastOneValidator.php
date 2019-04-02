<?php

namespace App\Validators\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class AtLeastOneValidator
 */
class AtLeastOneValidator extends ConstraintValidator
{
    /**
     * Checks if the passed value is valid.
     *
     * @param mixed $value The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     */
    public function validate($value, Constraint $constraint)
    {
        /** @var AtLeastOne $constraint */
        if (empty($value->getKeys())) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
