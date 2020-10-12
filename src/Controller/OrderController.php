<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Message\OrderConfirmationEmails;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends AbstractController
{

    /**
     * @Route("/order", name="order")
     * @return Response
     */
    public function placeOrder(MessageBusInterface $bus)
    {
        $bus->dispatch(new OrderConfirmationEmails(1));

        return new Response('Your order has been placed');
    }

    /**
     * @Route("/index_order", name="index_order")
     */
    public function index()
    {
        return $this->render('order/index.html.twig', [
            'controller_name' => 'OrderController',
        ]);
    }
}
