<?php

namespace App\Service;

use App\Entity\Article;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
    // Le panier n'étant pas enregistré en base de données (car temporaire), on va utiliser un autre objet
    // présent pour enregistrer les données : la session utilisateur.
    private $sessionInterface;

    // Injection de la dépendance 'SessionInterface' pour pouvoir utiliser la session dans le panier.
    public function __construct(SessionInterface $sessionInterface)
    {
        $this->sessionInterface = $sessionInterface;
    }

    // Fonction d'affichage du panier.
    public function getCart()
    {
        // Si le panier est vide, cette fonction renverra un tableau vide et un prix total égal à zéro.
        return $this->sessionInterface->get('cart', [
            'elements' => [],
            'total' => 0.0
        ]);
    }

    // Fonction d'ajout d'article dans le panier.
    public function addArticle(Article $article)
    {
        $cart = $this->getCart();
        // On va faire appel à l'ID de l'article pour le retrouver car l'id est toujours unique.
        $articleId = $article->getId();

        // Condition qui vérifie que des articles sont bien présents dans le panier.
        // Si le panier est vide, la condition renverra un tableau vide avec une quantité égale à zéro.
        if (!isset($cart['elements'][$articleId]))
        {
            $cart['elements'][$articleId] = [
                'article' => $article,
                'quantity' => 0
            ];
        }

        // Le total du montant du panier est égal au total existant plus le prix du dernier article ajouté.
        $cart['total'] = $cart['total'] + $article->getPrix();
        // Le total du nombre d'articles dans le panier est égal au total existant plus le dernier article ajouté.
        $cart['elements'][$articleId]['quantity'] = $cart['elements'][$articleId]['quantity'] + 1;

        // Enregistrement du panier dans la variable $cart dans la session courante.
        $this->sessionInterface->set('cart', $cart);
    }

    // Fonction de suppression d'article du panier.
    public function removeArticle(Article $article)
    {
        $cart = $this->getCart();
        $articleId = $article->getId();

        // Condition qui vérifie que si l'article sélectionné est déjà absent du panier,
        // il ne peut donc pas être supprimé, et donc il n'y a pas d'action à effectuer.
        if (!isset($cart['elements'][$articleId]))
        {
            return;
        }

        // Le total du montant du panier est égal au total existant moins le prix du dernier article retiré.
        $cart['total'] = $cart['total'] - $article->getPrix();
        // Le total du nombre d'articles dans le panier est égal au total existant moins le dernier article retiré.
        $cart['elements'][$articleId]['quantity'] = $cart['elements'][$articleId]['quantity'] - 1;

        // Condition qui vérifie que si le nombre d'articles dans le panier est égal à zéro, il faut vider le panier.
        if ($cart['elements'][$articleId]['quantity'] <= 0)
        {
            unset($cart['elements'][$articleId]);
        }

        // Enregistrement du panier dans la variable $cart dans la session courante.
        $this->sessionInterface->set('cart', $cart);
    }

    // Fonction de suppression du panier de la session.
    public function clearCart()
    {
        $this->sessionInterface->remove('cart');
    }
}