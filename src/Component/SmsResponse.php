<?php
namespace MessageBirdClient\Component;

/**
 * This class is used to store the responses obtained from the API. Further it can be used to display
 * messages in the interface.
 */
class SmsResponse
{
    /**
     * Messages obtained from the server side.
     * @var string
     */
    private $status;

    /**
     * Status code of the server response.
     * @var int
     */
    private $code;

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $code
     */
    public function setCode($code)
    {
        $this->code = $code;
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }
}
