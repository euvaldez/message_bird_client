<?php
namespace MessageBirdClient\Component;

use MessageBird\Client;
use MessageBird\Objects\Message;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Config\Definition\Exception\Exception;

/**
 * Send messages to API.
 */
class SendSmsMessage
{
    /**
     * Api request can be sent one request per second
     */
    const API_REQ_INTERVAL = '1 seconds';

    /**
     * Name of the reference to the last sms sent to the API
     */
    const LAST_SMS_MESSAGE_REFERENCE = 'sms_message.last_message';

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
     * The cache component that implements PSR-6. Used to store the last succesful result of a sent SMS.
     *
     * @var FilesystemAdapter
     */
    private $cache;

    /**
     * Initialize some classes.
     * @TODO move this initializations to the dependency injection container.
     */
    public function __construct()
    {
        $this->message_bird = new Client($this->production_access_key);
        $this->result       = new SmsResponse();
        $this->cache        = new FilesystemAdapter();
    }

    /**
     * Send curl request to MessageBird API.
     * @TODO Check what is the exact format.
     * This one does not work because API does not accept the keyword 'body' or 'message'
     * @param MessageRequest $request
     * @return SmsResponse
     */
    public function sendOneCurlRequest(MessageRequest $request)
    {
        $encoded_request = json_encode(
            [
                'recipients' => $request->getRecipients(),
                'originator' => $request->getSender(),
                'body' => $request->getMessage()->getMessage()
            ]
        );

        $headers = [
            'Content-Type: application/json',
            'Authorization: AccessKey ' . $this->testing_access_key
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
        $last_message_sent = $this->cache->getItem(self::LAST_SMS_MESSAGE_REFERENCE);
        $schedule_message  = $last_message_sent->isHit() ? true : false;

        dump($request->getMessage()->isMessageTooLong());
        if ($request->getMessage()->isMessageTooLong()) {
            // send multiple small messages
            foreach ($request->getMessage()->getConcatenatedMessage() as $sms_part) {
                $message = $this->buildBirdMessage($request, $schedule_message, $sms_part, true);
                $this->sendBirdMessage($message);
            }
        } else {
            $message = $this->buildBirdMessage($request, $schedule_message);
            $this->sendBirdMessage($message);
        }
        return $this->result;
    }

    /**
     *  Shows the current balance to send messages.
     * @return string
     */
    public function getBalance()
    {
        try {
            $balance = $this->message_bird->balance->read();
            return $balance->amount;
        } catch (\Exception $e) {
            return "Op dit moment is de SMS dienst niet beschikbaar. Probeer later nogmaals";
        }
    }

    /**
     * Store in cache the result form the last sent message. It will expire after 1 sec and then it will be deleted.
     * @param Message $result
     */
    private function storeResponseInCache(Message $result)
    {
        $cached_request = $this->cache->getItem(self::LAST_SMS_MESSAGE_REFERENCE);
        $cached_request->set($result);
        $cached_request->expiresAfter(\DateInterval::createFromDateString(self::API_REQ_INTERVAL));
        $this->cache->save($cached_request);
    }

    /**
     * Sends the message to the bird API.
     * @param Message $message
     */
    private function sendBirdMessage(Message $message)
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
            $this->storeResponseInCache($result);
        } catch (\Exception $e) {
            $this->result->setStatus($e->getMessage());
            $this->result->setCode($e->getCode());
        }
    }

    /**
     * Builds a message bird object to be sent via the API.
     *
     * @param MessageRequest         $request
     * @param boolean                $schedule_message
     * @param SmsConcatenatedMessage $binary_message
     * @param boolean                $is_binary_message
     * @return Message
     */
    private function buildBirdMessage(
        MessageRequest         $request,
        /* boolean */          $schedule_message,
        SmsConcatenatedMessage $binary_message = null,
        /* boolean */          $is_binary_message = false
    ) {
        $message             = new Message();
        $message->originator = $request->getSender();
        $message->recipients = $request->getRecipients();
        if ($is_binary_message) {
            $message->setBinarySms($binary_message->getHeader(), $binary_message->getMessage());
        } else {
            $message->body = $request->getMessage()->getMessage();
        }
        $little_later = new \DateTime();
        $little_later->add(\DateInterval::createFromDateString(self::API_REQ_INTERVAL));
        $message->scheduledDatetime = $schedule_message ? $little_later->format('Y-m-d H:i:s') : null;

        return $message;
    }
}
