<?php

namespace App\Service;

use Stripe\StripeClient;

class PaymentService
{
    private $cartService;
    private $stripe;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
        $this->stripe = new StripeClient('sk_test_51KKOuKJlloKEXjEO0vyV76PRB7AxukO8tsvQPkXxs2oKWkEJ9SZ0vFIfd6Zb10VDZDRJ9dSsMtCpnolzZe9wbdfx00msXnvNvm');
    }

    public function create(): string
    {
        $cart = $this->cartService->getCart();
        $items = [];
        foreach ($cart['elements'] as $articleId -> $element)
        {
            $items[] = [
                'amount' => $element['article']->getPrix() * 100,
                'quantity' => $element['quantity'],
                'currency' => 'eur',
                'name' => $element['article']->getTitre()
            ];
        }

        $protocol = $_SERVER['HTTPS'] ? 'https' : 'http';
        $host = $_SERVER['SERVER_NAME'];
        $successUrl = $protocol . '://' . $host . '/payment/success/(CHECKOUT_SESSION_ID)';
        $failureUrl = $protocol . '://' . $host . '/payment/failure/(CHECKOUT_SESSION_ID)';

        $session = $this->stripe->checkout->sessions->create([
            'success_url' => $successUrl,
            'failure_url' => $failureUrl,
            'payment_method_types' => ['card'],
            'mode' => 'payment',
            'line_items' => $items
        ]);

        return $session->id;
    }
}