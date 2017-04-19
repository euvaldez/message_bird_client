<?php
namespace MessageBirdClient\AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class TelephoneLength extends Constraint
{
    public $message = 'Telefoonnummer "%string%" is te kort of te lang.';
}