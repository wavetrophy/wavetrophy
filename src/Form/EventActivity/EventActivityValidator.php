<?php

namespace App\Form\EventActivity;

use Psr\Container\ContainerInterface;
use Symfony\Component\Form\Exception\InvalidConfigurationException;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class EventActivityValidator
 */
class EventActivityValidator extends ConstraintValidator
{

    /**
     * @var Request
     */
    private $request;

    /**
     * CoordinateValidator constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->request = $container->get('request_stack')->getCurrentRequest();
    }

    /**
     * Checks if the passed value is valid.
     *
     * @param mixed $value The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     */
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof EventActivity) {
            throw new UnexpectedTypeException($constraint, EventActivity::class);
        }

        $data = $this->request->request->all();
        $key = $constraint->name;

        if (empty($data) || empty($data[$key])) {
            $this->context->buildViolation('There needs to be at least one activity')->addViolation();
            return;
        }

        foreach ($data[$key]as $record) {
            if (!isset($record['title'])) {
                $this->context->buildViolation('Title is required')->addViolation();
            }
            if (!isset($record['start'])) {
                $this->context->buildViolation('Start is required')->addViolation();
            }
            if (!isset($record['end'])) {
                $this->context->buildViolation('End is required')->addViolation();
            }

            if (isset($record['title']) && empty($record['title'])) {
                $this->context->buildViolation('Title cannot be empty')->addViolation();
            }
            if (isset($record['start']) && empty($record['start'])) {
                $this->context->buildViolation('Start cannot be empty')->addViolation();
            }
            if (isset($record['end']) && empty($record['end'])) {
                $this->context->buildViolation('End cannot be empty')->addViolation();
            }
        }
    }
}
