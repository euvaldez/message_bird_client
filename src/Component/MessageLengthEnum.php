<?php
namespace MessageBirdClient\Component;

/**
 * In this class we set up the maximum lenght of the message before we concatenate it
 */
class MessageLengthEnum
{
    const MAX_LENGTH            = 160;
    const MIN_AMOUNT_CHARACTERS = 153;

    /**
     * Init empty construct because this is just an enum
     */
    public function __construct()
    {
    }
}
