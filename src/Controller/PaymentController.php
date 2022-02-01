<?php

namespace App\Controller;

use App\Entity\Article;
use App\Service\CartService;
use App\Service\PaymentService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PaymentController extends AbstractController
{
    /**
     * @Route("/payment/redirect", name="payment_redirect")
     */
    public function index (CartService $cartService, PaymentService $paymentService): Response
    {
        $cart = $cartService->getCart();
        $paymentSessionId = $paymentService->create($cart['total']);
        return $this->render('payment/redirect.html.twig', [
            'paymentSessionId' => $paymentSessionId,
            'controller_name' => 'ZEUS EX MACHINA : Transaction en cours...',
        ]);
    }

    /**
     * @Route("/payment/success/{stripeSessionId}", name="payment_success")
     */
    // Redirection vers la page de succès de paiement avec le numéro de la session Stripe
    public function success(): Response
    {
        return $this->render('payment/success.html.twig', [
            'controller_name' => 'ZEUS EX MACHINA : Transaction réussie !',
        ]);
    }

    /**
     * @Route("/payment/failure/{stripeSessionId}", name="payment_failure")
     */
    // Redirection vers la page d'échec de paiement avec le numéro de la session Stripe
    public function failure(): Response
    {
        return $this->render('payment/failure.html.twig', [
            'controller_name' => 'ZEUS EX MACHINA : Transaction échouée !',
        ]);
    }
}