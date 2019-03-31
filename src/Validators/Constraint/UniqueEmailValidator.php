<?php

namespace App\Validators\Constraint;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class UniqueEmailValidator
 */
class UniqueEmailValidator extends ConstraintValidator
{
    private $userRepository;

    /**
     * UniqueEmailValidator constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Checks if the passed value is valid.
     *
     * @param mixed $value The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     */
    public function validate($value, Constraint $constraint)
    {
        /** @var User $user */
        $user = $this->context->getObject();
        if ($value === (string)$user->getEmail()) {
            return;
        }
        /** @var UniqueEmail $constraint */
        $isAlreadyRegistered = $this->userRepository->isEmailAlreadyRegistered($value);
        if($isAlreadyRegistered) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
