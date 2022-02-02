<?php

namespace App\Controller;

use DateTime;
use App\Entity\Article;
use App\Entity\Commande;
use App\Entity\Paiement;
use App\Service\CartService;
use App\Service\PaymentService;
use Doctrine\ORM\EntityManager;
use App\Repository\ArticleRepository;
use App\Repository\PaiementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PaymentController extends AbstractController
{
    /**
     * @Route("/payment/redirect", name="payment_redirect")
     */
    public function index (CartService $cartService, PaymentService $paymentService, EntityManagerInterface $entityManager): Response
    {
        // Récupération des articles présents dans le panier.
        $cart = $cartService->getCart();
        // Récupération de la session Stripe liée au panier en cours.
        $paymentSessionId = $paymentService->create($cart['total']);

        // Création d'un paiement en attente.
        $paiement = new Paiement();
        $paiement->setCreation(new DateTime());
        $paiement->setStripeSessionId($paymentSessionId);
        $entityManager->persist($paiement);
        $entityManager->flush();
        return $this->render('payment/redirect.html.twig', [
            'paymentSessionId' => $paymentSessionId,
            'controller_name' => 'ZEUS EX MACHINA : Transaction en cours...',
        ]);
    }

    /**
     * @Route("/payment/success/{stripeSessionId}", name="payment_success")
     */
    // Redirection vers la page de succès de paiement avec le numéro de la session Stripe
    public function success(string $stripeSessionId, PaiementRepository $paiementRepository, CartService $cartService, EntityManagerInterface $entityManager): Response
    {
        // Récupération du paiement en attente créée dans la fonction index.
        $paiement = $paiementRepository->findOneBy([
            'stripeSessionId' => $stripeSessionId
        ]);
        
        // Si aucun paiement n'est trouvé, l'utilisateur est renvoyé vers le panier.
        if (!$paiement)
        {
            return $this->redirectToRoute('cart_index');
        }

        // Création de la commande
        $commande = new Commande();
        $commande->setCreation(new DateTime());
        $commande->setPaiement($paiement);
        $commande->setClient($this->getUser());
        $commande->setReference(strval(rand(1000000,99999999)));
        // Enregistrement de la commande par l'Entity Manager.
        $entityManager->persist($commande);

        /* Code à rentrer au cas où un même article peut être acheté plusieurs fois.
        $cart = $cartService->get();
        foreach ($cart['elements'] as $articleId => $element)
        {
            $article = $articleRepository->find($articleId);
            $commandeQuantite = new CommandeQuantite();
            $commandeQuantite->setQuantite($element['quantity']);
            $commandeQuantite->setArticle($article);
            $commandeQuantite->setCommande($commande);
            $entityManager->persist($commandeQuantite);
        }*/

        // Validation définitive de la commande par l'Entity Manager.
        $entityManager->flush();

        // Si le paiement est effectif, le paiement appelle la commande créée.
        $paiement->setCommande($commande);

        // Validation définitive du paiement par l'Entity Manager.
        $entityManager->flush();

        // Vidage du panier une fois la commande et le paiement terminés.
        $cartService->clearCart();

        return $this->render('payment/success.html.twig', [
            'controller_name' => 'ZEUS EX MACHINA : Transaction réussie !',
        ]);
    }

    /**
     * @Route("/payment/failure/{stripeSessionId}", name="payment_failure")
     */
    // Redirection vers la page d'échec de paiement avec le numéro de la session Stripe
    public function failure(string $stripeSessionId, PaiementRepository $paiementRepository, EntityManagerInterface $entityManager): Response
    {
        // Récupération de la session Stripe liée au panier en cours.
        $paiement = $paiementRepository->findOneBy([
            'stripeSessionId' => $stripeSessionId
        ]);

        // Si aucun paiement n'est trouvé, l'utilisateur est renvoyé vers le panier.
        if (!$paiement)
        {
            return $this->redirectToRoute('cart_index');
        }

        // Appel de l'Entity Manager pour supprimer le paiement erronné, et terminer l'opération.
        $entityManager->remove($paiement);
        $entityManager->flush();

        return $this->render('payment/failure.html.twig', [
            'controller_name' => 'ZEUS EX MACHINA : Transaction échouée !',
        ]);
    }
}