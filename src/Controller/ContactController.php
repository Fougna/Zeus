<?php

namespace App\Controller;

use App\Form\ContactType;
use App\Service\EmailService;
use App\Service\RememberService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(Request $requete, EmailService $emailService, RememberService $rememberService): Response
    {
        $contact = $this->createForm(ContactType::class, [
            // Ligne de code pour faire apparaître par défaut l'adresse mail suivante dans le champ E-mail (donnée constante).
            // 'email' => 'mon@email.com'

            // 1. On demande à rememberService d'afficher le mail de l'utilisateur enregistré plus bas dans la session.
            'email' => $rememberService->donneMoi('emailSaisi')
        ]);

        $contact->handleRequest($requete);
        
        if ($contact->isSubmitted())
        {
            $donnees = $contact->getData();
            $visiteur = $donnees['email'];
            $administrateur = 'admin@mon-super-site.com';
            $objet = $donnees['objet'];
            $message = $donnees['message'];

            // 2. On demande à RememberService d'enregistrer dans la session le mail rentré par l'utilisateur.
            $rememberService->seSouvenir('emailSaisi', $visiteur);

            // 3. On demande à EmailService d'envoyer le mail du visiteur vers l'administrateur.
            $emailService->envoyer($visiteur, $administrateur, $objet, 'email/envoi.html.twig', [
                'message' => $message
            ]);

            // 4. On demande à EmailService d'envoyer un mail d'accusé de réception de l'administrateur vers le mail du visiteur.
            $emailService->envoyer($administrateur, $visiteur, 'RE: ' . $objet, 'email/accuse.html.twig', [
                'visiteur' => $visiteur
            ]);

            // 5a. Une fois les données envoyées, on redirige l'utilisateur vers une page indiquant que le mail a bien été envoyé.
            return $this->render('contact/success.html.twig', [
                // 5b. On affiche dans la nouvelle page les informations suivantes.
                'mail' => $visiteur,
                'objet' => $objet,
                'message' => $message,
                'controller_name' => 'ZEUS EX MACHINA : Message envoyé !',
            ]);
            // 6. Si le mail n'a pas été complètement rempli, on redirige l'utilisateur vers la page du formulaire 'Contact' à remplir.
        } else {
            return $this->renderForm('contact/index.html.twig', [
                'formulaireAfficher' => $contact,
                'controller_name' => 'ZEUS EX MACHINA : Contactez-nous',
            ]);
        }
    }
}