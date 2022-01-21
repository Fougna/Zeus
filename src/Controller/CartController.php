<?php

namespace App\Controller;

use App\Entity\Article;
use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/panier", name="cart_index")
     */
    public function index(CartService $cartService): Response
    {
        $cart = $cartService->getCart();
        return $this->render('cart/index.html.twig', [
            'controller_name' => 'ZEUS EX MACHINA : Panier',
            'cart' => $cart
        ]);
    }

    /**
     * @Route("/panier/ajout/{id}", name="cart_add")
     */
    public function add(CartService $cartService, Article $article): Response
    {
        $cartService->addArticle($article);
        return $this->redirectToRoute('cart_index');
    }

    /**
     * @Route("/panier/retrait/{id}", name="cart_remove")
     */
    public function remove(CartService $cartService, Article $article): Response
    {
        $cartService->removeArticle($article);
        return $this->redirectToRoute('cart_index');
    }

    /**
     * @Route("/panier/vide", name="cart_clear")
     */
    public function clear(CartService $cartService): Response
    {
        $cartService->clearCart();
        return $this->redirectToRoute('cart_index');
    }
}