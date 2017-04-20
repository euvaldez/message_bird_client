<?php
namespace MessageBirdClient\AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Validates the telephone number used to send the SMS.
 */
class TelephoneLengthValidator extends ConstraintValidator
{
    const MIN_LENGTH = 10;
    const MAX_LENGTH = 12;
    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        foreach ($value as $phone) {
            if (strlen($phone) < self::MIN_LENGTH || strlen($phone) > self::MAX_LENGTH) {
                $this->context->buildViolation($constraint->message)->setParameter('%string%', $phone)->addViolation();
            }
            break;
        }
    }
}
