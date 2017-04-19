<?php
namespace MessageBirdClient\AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class MessageContent extends Constraint
{
    public $message = 'Leeg bericht "%string%". Voer je bericht in.';
}