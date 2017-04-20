<?php
/**
 * @copyright 2017 Eunice Valdez.
 */
namespace MessageBirdClient\Component;

use PHPUnit\Framework\TestCase;

/**
 * @covers \MessageBirdClient\Component\SmsMessage
 */
class SmsMessageTest extends TestCase
{
    private $message;

    /**
     * @var SmsMessage
     */
    private $sms_message;

    protected function setUp()
    {
        $this->message = '';

        $this->sms_message = new SmsMessage(
            $this->message
        );
    }

    /**
     * @dataProvider shortMessagesProvider
     */
    public function testGetMessage($message_input, $expected_response)
    {
        $short_sms     = new SmsMessage($message_input);
        $processed_msg = $short_sms->getMessage();
        $this->assertEquals(1, preg_match('/^' . $expected_response . '/', $processed_msg, $matches));
    }

    /**
     * @dataProvider longMessagesProvider
     */
    public function testGetConcatenatedMessage($message_input, $expected_header)
    {
        $concatenated_message = new SmsMessage($message_input);
        $processed_msg        = $concatenated_message->getConcatenatedMessage();
        foreach ($processed_msg as $sms) {
            $this->assertContains($expected_header, $sms->getHeader());
            $this->assertNotEmpty($sms->getMessage());
        }
    }

    public function shortMessagesProvider()
    {
        return [
            ['', ''],
            ['Deze is een kort bericht die hoef niet om concatenated te maken', 'Deze is een kort bericht']
        ];
    }

    public function longMessagesProvider()
    {
        return [
            ['Beste Arjan, ' .
                'Ik ben aan het testen een hele lang bericht. ' .
                'Ik moest een vehaal maken zodat deze bericht heeft meer dan 156 karakters. ' .
                'Met deze zal ik aan jou sturen kleine berichten in een volgorde. ' .
                'Wat grappig het is dat je krijg een hele laang message. Dus ziet je niet als apart sms maar als een.' .
                'Ik stuur aan jou een grappig verhaaltje' .
                'Als er een ongewoon lange, ongeschoren, glimmende, en een onaangenaam penetrante bloemetjesgeur ' .
                'verspreidende landloper die al zeker een maand geen bad van dichtbij gezien heeft, ' .
                'met een blik bier van een niet nader te noemen huismerk in zijn binnenzak, ' .
                'zou liggen slapen op zijn meest geliefde, twee weken geleden nog gelakte bankje; ' .
                'onder de misschien wel honderd jaar oude eik waar ik als kind met een schroefje mijn ' .
                'naam in kerfde, zouden er op dit moment twee oude vrouwtjes met vriendelijk doch gerimpeld gelaat, ' .
                'die niet voorzien zijn van twee handige blauwe rollators met zo’n handig rekje voorop waar ' .
                'die oudjes hun handtassen in kunnen plaatsen zodat ze zich met beide handen vast kunnen klampen ' .
                'aan de vertrouwde stevigheid van het zelfs met handremmen voorziene karretje, uitgeput op de grond liggen.' .
                'Maar daar dit niet het geval is; een vriendelijk ogende meneer, met fonkelende pretogen en een ' .
                'stoppelbaard van slechts enkele dagen; een zachte bloemetjesgeur verspreidend, ' .
                'en zittend op het uiterste puntje van het onder de oude eik gelegen, pasgelakte bankje – dat ' .
                'fonkelt in het warme zonlicht – twee naderende oude vrouwtjes hoffelijk een goede dag wenst, ' .
                'en ze aanbiedt naast hem te komen zitten en te genieten van het mooie weer en de luwte van de oude eik, ' .
                'omdat hij dit stukje geluk en ook genot niet voor zichzelf wil houden maar wil delen met zijn ' .
                'medemensen tijdens het samen converseren over koetjes, kalfjes, ' .
                'het weer en de schoonheid van de natuur – daar dit zo is, ziet deze landloper zijn geluk ' .
                'terug in kleine dingen en oude vrouwtjes, en terwijl hij het geritsel van de bladeren van de ' .
                'oude eik als muziek op zich in laat werken, pinkt hij voorzichtig, zonder dat iemand het ziet, ' .
                'een traantje weg – een traantje van geluk.', '684']
        ];
    }
}
