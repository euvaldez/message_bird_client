<?php
namespace MessageBirdClient\AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class SenderContent extends Constraint
{
    public $message = 'Afzender "%string%" is leeg. Voer je naam in.';
}
