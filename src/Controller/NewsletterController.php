<?php

namespace App\Controller;

use App\Form\NewsletterType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class NewsletterController extends AbstractController
{
    /**
     * @Route("/newsletter", name="newsletter")
     */
    public function index(Request $requete): Response
    {
        $formulaire = $this->createForm(NewsletterType::class);
        $formulaire->handleRequest($requete);
        if ($formulaire->isSubmitted())
        {
            $donnees = $formulaire->getData();
            $firstname = $donnees['prenom'];
            $lastname = $donnees['nom'];
            $email = $donnees['mail'];
            return $this->render('newsletter/success.html.twig',
            [
                'prenom' => $firstname,
                'nom' => $lastname,
                'mail' => $email,
                'controller_name' => 'ZEUS EX MACHINA : Inscription réussie !',
            ]);
        }
        else
        {
            return $this->renderForm('newsletter/index.html.twig',
            [
                'formulaireAfficher' => $formulaire,
                'controller_name' => 'ZEUS EX MACHINA : Inscription à la Newsletter',
            ]);
        }
    }
}