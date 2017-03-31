<?php

namespace AppBundle\Controller;

use MessageBird\Client;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
        $balance = $message_bird->balance->read();

        return $this->render('default/messengerBird.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'balance' => $balance
        ]);
    }
}
