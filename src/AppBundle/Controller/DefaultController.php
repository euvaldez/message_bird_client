<?php

namespace AppBundle\Controller;

use MessageBird\Client;
use MessageBirdClient\Component\MessageRequest;
use MessageBirdClient\Component\SendSmsMessage;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * Action to display the nain page that will submit the message to message bird API.
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/bird_messenger", name="homepage")
     */
    public function sendMessageAction(Request $request)
    {
        // get the balance
        $message_bird = new Client('aOoy44PcEROkan2d5dTZCnmEy');
        $balance      = $message_bird->balance->read();
        $message_bird = new MessageRequest();

        $form = $this->createFormBuilder($message_bird)
            ->add('recipients', TextareaType::class)
            ->add('sender', TextType::class)
            ->add('message', TextareaType::class)
            ->add('send', SubmitType::class, ['label' => 'Verzenden'])
            ->getForm();

        $form->handleRequest($request);

        $result = '';
        if ($form->isSubmitted() && $form->isValid()) {
            $send_message = new SendSmsMessage();
            $result       = $send_message->sendOneRequest($form->getData());
            dump($result);
        }

        return $this->render('default/messengerBird.html.twig', [
            'balance' => $balance,
            'form'    => $form->createView(),
            'result'  => $result
        ]);
    }
}
