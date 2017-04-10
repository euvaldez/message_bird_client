<?php
namespace MessageBirdClient\AppBundle\Controller;

use MessageBirdClient\Component\MessageRequest;
use MessageBirdClient\Component\SendSmsMessage;
use MessageBirdClient\Component\SmsResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * Action to display the main page that will submit the message to message bird API.
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/bird_messenger", name="homepage")
     */
    public function sendMessageAction(Request $request)
    {
        $message_bird = new MessageRequest();
        $send_message = new SendSmsMessage();

        $form = $this->createFormBuilder($message_bird)
            ->add('recipients', TextareaType::class)
            ->add('sender', TextType::class)
            ->add('message', TextareaType::class)
            ->add('send', SubmitType::class, ['label' => 'Verzenden'])
            ->getForm();

        $form->handleRequest($request);


        $result = '';
        if ($form->isSubmitted() && $form->isValid()) {
            $result = $send_message->sendOneCurlRequest($form->getData());
            dump($result);
        }

        return $this->render('default/messengerBird.html.twig', [
            'balance' => $send_message->getBalance(),
            'form'    => $form->createView(),
            'result'  => $result
        ]);
    }
}
