<?php
namespace MessageBirdClient\Component;

/**
 * Stores the content of One message.
 */
class SmsMessage
{
    /**
     * @var string
     */
    private $message;

    /**
     * @param $message
     */
    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
     * Validate message:
     * Should not be empty
     * @return bool
     */
    public function isValid()
    {
        if (empty($this->message)) {
            return false;
        }
        // @TODO add other validation rules
        return true;
    }

    /**
     * Check the length of the message.
     *
     * @return bool
     */
    public function isMessageTooLong()
    {
        return $this->message >= MessageLengthEnum::MAX_LENGTH;
    }

    /**
     * If the message is longer than 160 concatenate it and then return it
     * @return string
     */
    public function getMessage()
    {
        if ($this->isMessageTooLong()) {
            // @TODO here concatenate the message according the specifications
        }
        return $this->message;
    }
}