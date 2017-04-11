<?php
namespace MessageBirdClient\Component;

use MessageBirdClient\AppBundle\Validator\Constraints as AcmeAssert;

/**
 * Stores a request to be sent to the client.
 */
class MessageRequest
{
    const TYPE = "POST";

    /**
     * Store the recipients of the SMS message.
     * @var string
     * Specify custom validation rules for the recipient.
     * @AcmeAssert\DutchTelephone
     * @AcmeAssert\TelephoneLength
     */
    private $recipients;

    /**
     * Store the sender of the SMS.
     * @var string
     * Specify custom validation rules for the sender.
     * @AcmeAssert\SenderContent
     */
    private $sender;

    /**
     * Store the content of the message itself
     * @var SmsMessage
     * Specify custom validation rules for the message.
     * @AcmeAssert\MessageContent
     */
    private $message;

    /**
     * @param int $recipients
     * @TODO this should be an array of recipients.
     */
    public function setRecipients($recipients)
    {
        $this->recipients = $recipients;
    }

    /**
     * @param string $sender
     */
    public function setSender($sender)
    {
        $this->sender = $sender;
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = new SmsMessage($message);
    }

    /**
     * @return string
     */
    public function getType()
    {
        return self::TYPE;
    }

    /**
     * @return int
     */
    public function getRecipients()
    {
        return intval($this->recipients);
    }

    /**
     * @return string
     */
    public function getSender()
    {
        return $this->sender;
    }

    /**
     * @return SmsMessage
     */
    public function getMessage()
    {
        return $this->message;
    }
}