<?php

namespace App\Service;

use App\Entity\Article;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
    private $sessionInterface;

    public function __construct(SessionInterface $sessionInterface)
    {
        $this->sessionInterface = $sessionInterface;
    }

    public function getCart()
    {
        return $this->sessionInterface->get('cart', [
            'elements' => [],
            'total' => 0.0
        ]);
    }

    public function addArticle(Article $article)
    {
        $cart = $this->getCart();
        $articleId = $article->getId();

        if (!isset($cart['elements'][$articleId]))
        {
            $cart['elements'][$articleId] = [
                'article' => $article,
                'quantity' => 0
            ];
        }

        $cart['total'] = $cart['total'] + $article->getPrix();
        $cart['elements'][$articleId]['quantity'] = $cart['elements'][$articleId]['quantity'] + 1;

        $this->sessionInterface->set('cart', $cart);
    }

    public function removeArticle(Article $article)
    {
        $cart = $this->getCart();
        $articleId = $article->getId();

        if (!isset($cart['elements'][$articleId]))
        {
            return;
        }

        $cart['total'] = $cart['total'] - $article->getPrix();
        $cart['elements'][$articleId]['quantity'] = $cart['elements'][$articleId]['quantity'] - 1;

        if ($cart['elements'][$articleId]['quantity'] <= 0)
        {
            unset($cart['elements'][$articleId]);
        }

        $this->sessionInterface->set('cart', $cart);
    }

    public function clearCart()
    {
        $this->sessionInterface->remove('cart');
    }
}