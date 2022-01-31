<?php

namespace App\Service;

use Stripe\StripeClient;

class PaymentService
{
    private $order;
    private $cartService;

    public function __construct()
    {
        $this->order = new StripeClient('sk_test_51KKOuKJlloKEXjEO0vyV76PRB7AxukO8tsvQPkXxs2oKWkEJ9SZ0vFIfd6Zb10VDZDRJ9dSsMtCpnolzZe9wbdfx00msXnvNvm');
    }

    public function create(): string
    {
        $this->order->checkout->sessions->create([
            'success_url' => 'http://localhost:8000/payment/success',
            'cancel_url' => 'http://localhost:8000/payment/failure',
            'payment_method_types' =>[
                'card'
            ],
            'mode' => 'payment',
            'line_items' => [
                // Ajout de dictionnaires (On peut en mettre plusieurs).
                [
                    // Montant toujours affiché en centimes (20€ = 2000 centimes)
                    'amount' => $prix * 100,
                    'quantity' => 1,
                    'currency' => 'eur',
                    'name' => 'Total des achats'
                ]
            ]
        ]);

        return $session->id;
    }
}