<?php
namespace MessageBirdClient\Component;

use MessageBird\Client;
use MessageBird\Objects\Message;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * Send messages to API.
 */
class SendSmsMessage
{
    /**
     * @var string
     */
    private $api_url = 'https://rest.messagebird.com/messages';

    private $message_bird;

    public function __construct()
    {
        //$this->message_bird = new Client('aOoy44PcEROkan2d5dTZCnmEy');
        $this->message_bird = new Client('aOoy44PcEROkan2d5dTZCnmEy');
    }

    public function connectToMessageBird(MessageRequest $request)
    {
        $curl_handle = curl_init($this->cms_api_url . rawurlencode($request->getTemplateName()));
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 60);
        curl_setopt($curl_handle, CURLOPT_TIMEOUT, 60);
        curl_setopt($curl_handle, CURLOPT_CUSTOMREQUEST, $request->getType());
        $server_response = curl_exec($curl_handle);
        $status_code     = curl_getinfo($curl_handle, CURLINFO_HTTP_CODE);
        curl_close($curl_handle);
    }
    public function sendOneRequest(MessageRequest $request)
    {
        //convert the message to Json
        $message             = new Message();
        $message->originator = $request->getSender();
        $message->recipients = [$request->getRecipients()];
        $message->body       = $request->getMessage()->getMessage();

        // send message
        try {
            $result = $this->message_bird->messages->create($message);
        } catch (\Exception $e) {
            $result = $e->getMessage();
        }

        //check for errors
        return $result;
    }

    public function storeMessageInQueue()
    {
        // if too many requests come store them here to send it later
    }
}