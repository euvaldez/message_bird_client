<?php
namespace MessageBirdClient\Component;

/**
 * Stores a request to be sent to the client.
 */
class MessageRequest
{
    const TYPE = "POST";

    /**
     * Store the recipients of the SMS message.
     * @var string
     */
    private $recipients;

    /**
     * Store the sender of the SMS.
     * @var string
     */
    private $sender;

    /**
     * Store the content of the message itself
     * @var SmsMessage
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