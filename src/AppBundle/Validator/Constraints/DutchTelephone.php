<?php
namespace MessageBirdClient\AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class DutchTelephone extends Constraint
{
    public $message = 'De telefoon nummer "%string%" heeft niet de juiste format. '
    . 'Een nederlandse telefoon nummer is verwacht';
}
