<?php
namespace MessageBirdClient\Component;

/**
 * Stores one part of the message to be send in succesive concatenated messages.
 */
class SmsConcatenatedMessage
{
    /**
     * @var string
     */
    private $header;

    /**
     * @var string
     */
    private $message;

    /**
     * @param string $header
     * @param string $message
     */
    public function __construct($header, $message)
    {
        $this->header  = $header;
        $this->message = $message;
    }

    /**
     * @return string
     */
    public function getHeader(): string
    {
        return $this->header;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }
}