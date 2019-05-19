<?php

namespace App\Form\Coordinate;

use Psr\Container\ContainerInterface;
use Symfony\Component\Form\Exception\InvalidConfigurationException;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class CoordinateValidator
 */
class CoordinateValidator extends ConstraintValidator
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
        if (!$constraint instanceof Coordinate) {
            throw new UnexpectedTypeException($constraint, Coordinate::class);
        }

        $data = $this->request->request->all();
        $key = $constraint->name;

        if (!isset($data[$key])) {
            throw new InvalidConfigurationException("{$key} is expected to be present in form data within custom Coordinate constraint");
        }

        if (empty($data) || empty($data[$key])) {
            $this->context->buildViolation('Location cannot be empty')->addViolation();
        }
    }
}
