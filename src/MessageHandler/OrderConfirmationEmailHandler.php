<?php
/**
 * Created by PhpStorm.
 * User: chane
 * Date: 12/10/2020
 * Time: 10:30
 */

namespace App\MessageHandler;


use App\Message\OrderConfirmationEmails;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class OrderConfirmationEmailHandler implements MessageHandlerInterface
{

    public function __invoke(OrderConfirmationEmails $orderConfirmationEmails)
    {
        // Query order / custome details from db

        // Create email from template

        // Send email
        echo 'Sending email now ...';

        // other stuff which takes a while ...
    }



}