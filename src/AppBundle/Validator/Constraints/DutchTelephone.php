<?php
namespace MessageBirdClient\AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class DutchTelephone extends Constraint
{
    public $message = 'Het telefoonnummer "%string%" heeft niet de juiste format. '
    . 'Een Nederlands telefoonnummer wordt verwacht';
}
