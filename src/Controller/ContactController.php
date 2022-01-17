<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/contact", name="contact")
     */
    public function index(Request $requete): Response
    {
        $formulaire = $this->createForm(ContactType::class);
        $formulaire->handleRequest($requete);
        if ($formulaire->isSubmitted())
        {
            $donnees = $formulaire->getData();
            $email = $donnees['mail'];
            $sujet = $donnees['objet'];
            $contenu = $donnees['message'];
            return $this->render('contact/success.html.twig',
            [
                'mail' => $email,
                'objet' => $sujet,
                'message' => $contenu,
                'controller_name' => 'ZEUS EX MACHINA : Message envoyé !',
            ]);
        }
        else
        {
            return $this->renderForm('contact/index.html.twig',
            [
                'formulaireAfficher' => $formulaire,
                'controller_name' => 'ZEUS EX MACHINA : Contact',
            ]);
        }
    }
}