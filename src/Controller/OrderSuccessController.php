<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Classe\Mail;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderSuccessController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/commande/merci/{stripeSessionId}", name="order_validate")
     */
    public function index(Cart $cart,$stripeSessionId): Response
    {

        $order = $this->entityManager->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);

        if(!$order || $order->getUser() != $this->getUser()){
            return $this->redirectToRoute('home');
        }

        if(!$order->getIsPaid())
        {
        // vider la session cart
            $cart->remove();
        // Modifier isPaid
            $order->setIsPaid(1);
            $this->entityManager->flush();
        // Envoyer email
            $email = new Mail();
            $content ="Bonjour".$order->getUser()->getFirstname()." votre commande est valider";
            $email->send($order->getUser()->getEmail(), $order->getUser()->getFirstname(),'Bienvenue sur devstyle',$content);

        }



        // aficher les infos de la commande
        return $this->render('order_success/index.html.twig', [
            'order' => $order
        ]);
    }
}
