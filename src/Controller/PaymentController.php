<?php

namespace App\Controller;

use App\Entity\Article;
use App\Service\PaymentService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PaymentController extends AbstractController
{
    /**
     * @Route("/payment/redirect/{id}", name="payment_redirect")
     */
    public function index (Article $article, PaymentService $paymentService): Response
    {
        $paymentSessionId = $paymentService->create($article->getPrix() / 2);
        return $this->render('payment/redirect.html.twig', [
            'paymentSessionId' => $paymentSessionId,
        ]);
    }

    /**
     * @Route("/payment/success", name="payment_success")
     */
    public function success(): Response
    {
        return $this->render('payment/success.html.twig', [
            'controller_name' => 'Transaction réussie !',
        ]);
    }

    /**
     * @Route("/payment/failure", name="payment_failure")
     */
    public function failure(): Response
    {
        return $this->render('payment/failure.html.twig', [
            'controller_name' => 'Transaction échouée !',
        ]);
    }
}