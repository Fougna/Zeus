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
    // Fonction d'affichage de la liste des articles dans le panier
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
    // Fonction d'ajout d'articles dans le panier.
    // Ici, on utilise le param converter de Symfony pour convertir l'id de l'article en article.
    public function add(CartService $cartService, Article $article): Response
    {
        $cartService->addArticle($article);
        return $this->redirectToRoute('cart_index');
    }

    /**
     * @Route("/panier/retrait/{id}", name="cart_remove")
     */
    // Fonction de suppression d'articles du panier.
    public function remove(CartService $cartService, Article $article): Response
    {
        $cartService->removeArticle($article);
        return $this->redirectToRoute('cart_index');
    }

    /**
     * @Route("/panier/vide", name="cart_clear")
     */
    // Fonction de vidage du panier.
    public function clear(CartService $cartService): Response
    {
        $cartService->clearCart();
        return $this->redirectToRoute('cart_index');
    }
}