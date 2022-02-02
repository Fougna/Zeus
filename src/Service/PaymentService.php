<?php

namespace App\Service;

use Stripe\StripeClient;
use App\Service\CartService;

class PaymentService
{
    private $order;
    private $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
        $this->order = new StripeClient('sk_test_51KKOuKJlloKEXjEO0vyV76PRB7AxukO8tsvQPkXxs2oKWkEJ9SZ0vFIfd6Zb10VDZDRJ9dSsMtCpnolzZe9wbdfx00msXnvNvm');
    }

    // Création d'un ordre de paiement utilisant une session Stripe
    public function create(): string
    {
        $cart = $this->cartService->getCart();
        // Construction d'une liste d'éléments présents dans le panier dans un tableau vide.
        $articles = [];
        // Boucle pour passer en revue tous les articles présents dans le panier.
        foreach ($cart['elements'] as $articleId => $element)
        {
            // Construction des éléments qui figureront sur le ticket de caisse virtuel.
            $articles[] = [
                // Stripe n'acceptant pas les chiffres décimaux, le prix doit être multiplié par 100.
                'amount' => $element['article']->getPrix() * 100,
                'quantity' => $element['quantity'],
                'currency' => 'eur',
                'name' => $element['article']->getTitre()
            ];
        }

        // Comme on ne connaît pas à l'avance l'adresse URL où le site sera déployé,
        // on crée une variable 'protocole' qui affichera 'https' ou 'http'
        // si le serveur détecté est sécurisé ou non.
        $protocole = $_SERVER['HTTPS'] ? 'https' : 'http';
        // Assignation du nom du serveur dans une variable 'hote'.
        $hote = $_SERVER['SERVER_NAME'];
        // Concaténation des variables 'protocole' et 'hote' avec les adresses de succès et d'échec de paiement.
        $successUrl = $protocole. '://' . $hote . '/payment/success/{CHECKOUT_SESSION_ID}';
        $cancelUrl = $protocole. '://' . $hote . '/payment/failure/{CHECKOUT_SESSION_ID}';

        // Création de la session Stripe.
        $session = $this->order->checkout->sessions->create([
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
            'payment_method_types' =>['card'],
            'mode' => 'payment',
            'line_items' => $articles
        ]);

        // Récupération de l'id de la session Stripe en mémoire.
        return $session->id;
    }
}