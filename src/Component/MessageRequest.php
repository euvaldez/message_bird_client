<?php
namespace MessageBirdClient\Component;

/**
 * Stores a request to be sent to the client.
 */
class MessageRequest
{
    const TYPE = "POST";
    /**
     * @var string
     */
    private $recipients;

    /**
     * @var string
     */
    private $sender;

    /**
     * @var string
     */
    private $message;

    public function setRecipients($recipients)
    {
        $this->recipients = $recipients;
    }

    public function setSender($sender)
    {
        $this->sender = $sender;
    }

    public function setMessage($message)
    {
        $this->message = new SmsMessage($message);
    }

    public function getType()
    {
        return self::TYPE;
    }

    public function getRecipients()
    {
        return intval($this->recipients);
    }

    public function getSender()
    {
        return $this->sender;
    }

    public function getMessage()
    {
        return $this->message;
    }
}