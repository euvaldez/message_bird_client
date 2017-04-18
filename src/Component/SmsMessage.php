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
     * @var string
     */
    private $base_header_long_messages;

    /**
     * Stores concatenated messages that will be sent one by one.
     * @var SmsConcatenatedMessage[]
     */
    private $concatenated_messages = [];

    /**
     * @param string $message
     */
    public function __construct($message)
    {
        $this->message                   = $message;
        $this->base_header_long_messages = $this->buildUserDataHeader();
    }

    /**
     * If the message is longer than 160 concatenate it and then return it
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @TODO Build up a message builder that receives the message and process it building one or more of this
     * objects
     * @return SmsConcatenatedMessage[]
     */
    public function getConcatenatedMessage()
    {
        // Split the message in chunks of 153 characters
        $splited_message       = wordwrap($this->message, MessageLengthEnum::MIN_AMOUNT_CHARACTERS, '#&');
        $list_splited_messages = explode('#&', $splited_message);
        //print_r($list_splited_messages);
        // then each chunck encode it as hexadecimal and add the headers
        $sequence_parts = 1;
        foreach ($list_splited_messages as $msg) {
            $this->concatenated_messages[] = new SmsConcatenatedMessage(
                $this->base_header_long_messages . $sequence_parts,
                urlencode($msg)
            );
            //$hex_msg .= $this->base_header_long_messages . $sequence_parts . urlencode($msg);
            $sequence_parts++;
        }
        return $this->concatenated_messages;
    }

    /**
     * Check the length of the message.
     *
     * @return bool
     */
    public function isMessageTooLong()
    {
        return strlen($this->message) >= MessageLengthEnum::MAX_LENGTH;
    }

    /**
     * Build the header to concatenate the messages. Use 16-bit CSMS reference number.
     *
     * @return string The hexadecimal representation of the header values.
     */
    private function buildUserDataHeader()
    {
        // The header part contains commands IEs (Information elements):
        // an Identity Element Identifier (IEI)
        // followed by the Length of the IE Data (IEDL)
        // followed by the IE Data (IED)

        $length_data_user_header        = dechex(6);
        $information_element_identifier = dechex(8);
        $length_header                  = dechex(4);
        $reference_number               = dechex(rand(0, 15)); //number from 0000 to FFFF. 8 octets
        $message_portion                = ceil(strlen($this->message) / MessageLengthEnum::MIN_AMOUNT_CHARACTERS);
        $total_number_parts             = dechex($message_portion);

        return
            $length_data_user_header .
            $information_element_identifier .
            $length_header .
            $reference_number .
            $total_number_parts;
    }
}