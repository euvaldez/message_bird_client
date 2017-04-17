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
     * Url to connect to the API of the client.
     * @var string
     */
    private $api_url = 'https://rest.messagebird.com/messages';

    /**
     * Access key for the testing environment
     * @var string
     */
    private $testing_access_key = 'VLZZLj2CCqGa8v7FV3qQrQbBL';

    /**
     * Access key for the production environment
     * @var string
     */
    private $production_access_key = 'aOoy44PcEROkan2d5dTZCnmEy';

    /**
     * With this class we can send requests to the API.
     * @var Client
     */
    private $message_bird;

    /**
     * Store the response obtained from the API for further processing.
     * @var SmsResponse
     */
    private $result;

    /**
     * Initialize some classes.
     * @TODO move this initialization to the dependency injection container.
     */
    public function __construct()
    {
        $this->message_bird = new Client($this->production_access_key);
        $this->result       = new SmsResponse();
    }

    /**
     * Send curl request to MessageBird API.
     * @param MessageRequest $request
     * @return SmsResponse
     */
    public function sendOneCurlRequest(MessageRequest $request)
    {
        $encoded_request = json_encode(
            [
                'recipients'  => $request->getRecipients(),
                'originator' => $request->getSender(),
                'body'    => $request->getMessage()->getMessage()
            ]
        );

        $headers = [
            'Content-Type: application/json',
            'Authorization: AccessKey '. $this->production_access_key
        ];

        $curl_handle = curl_init($this->api_url);
        curl_setopt($curl_handle, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $encoded_request);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl_handle, CURLOPT_CUSTOMREQUEST, $request->getType());
        $server_response = curl_exec($curl_handle);
        $status_code     = curl_getinfo($curl_handle, CURLINFO_HTTP_CODE);
        curl_close($curl_handle);
        $this->result->setStatus(json_decode($server_response));
        $this->result->setCode($status_code);

        return $this->result;
    }

    /**
     * Send requests to message bird using the classes inside the API.
     * @param MessageRequest $request
     * @return SmsResponse
     */
    public function sendOneRequest(MessageRequest $request)
    {
        $message             = new Message();
        $message->originator = $request->getSender();
        $message->recipients = [$request->getRecipients()];

        if ($request->getMessage()->isMessageTooLong()) {
            // send multiple small messages
            foreach ($request->getMessage()->getConcatenatedMessage() as $sms_part) {
                $message             = new Message();
                $message->originator = $request->getSender();
                $message->recipients = [$request->getRecipients()];
                $message->setBinarySms($sms_part->getHeader(), $sms_part->getMessage());
                // send message
                $this->sendMessage($message);
            }

        } else {
            // send single message
            $message->body = $request->getMessage()->getMessage();
            // send message
            $this->sendMessage($message);
        }
        return $this->result;
    }

    /**
     * Store in cache the incoming messages if there are too many.
     */
    public function storeMessageInQueue()
    {
        // if too many requests come store them here to send it later
    }

    /**
     *  Shows the current balance to send messages.
     * @return string
     */
    public function getBalance()
    {
        return $this->message_bird->balance->read();
    }

    /**
     * Sends the message to the message bird API.
     * @param Message $message
     */
    private function sendMessage($message)
    {
        // send message
        try {
            $result = $this->message_bird->messages->create($message);
            dump($result);
            $status = '';
            foreach ($result->recipients->items as $recipient) {
                $status .= sprintf("%s : %s \n", $recipient->recipient, $recipient->status);
            }
            $this->result->setStatus($status);

        } catch (\Exception $e) {
            $this->result->setStatus($e->getMessage());
            $this->result->setCode($e->getCode());
        }
    }
}