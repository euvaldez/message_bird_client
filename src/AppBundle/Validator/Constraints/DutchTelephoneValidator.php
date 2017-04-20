<?php
namespace MessageBirdClient\AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Validates the telephone number used to send the SMS.
 */
class DutchTelephoneValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     * @TODO fix this regex to accept 316 without +. should I?
     */
    public function validate($value, Constraint $constraint)
    {
        foreach ($value as $phone) {
            if (preg_match(
                "/(^\+[0-9]{2}|^\+[0-9]{2}\(0\)|^\(\+[0-9]{2}\)\(0\)|^00[0-9]{2}|^0)([0-9]{9}$|[0-9\-\s]{11}$)/",
                $phone,
                $matches
            )
                === 0
            ) {
                $this->context->buildViolation($constraint->message)->setParameter('%string%', $phone)->addViolation();
                break;
            }
        }
    }
}
