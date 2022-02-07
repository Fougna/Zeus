<?php

namespace App\Controller;

use App\Entity\Newsletter;
use App\Form\NewsletterType;
use App\Repository\NewsletterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NewsletterController extends AbstractController
{
    /**
     * @Route("/newsletter", name="newsletter_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $newsletter = new Newsletter();
        $form = $this->createForm(NewsletterType::class, $newsletter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($newsletter);
            $entityManager->flush();

            return $this->render('newsletter/success.html.twig', [
                'newsletter' => $newsletter,
                'controller_name' => 'ZEUS EX MACHINA : Inscription réussie !'
            ]);
        }

        return $this->renderForm('newsletter/new.html.twig', [
            'newsletter' => $newsletter,
            'form' => $form,
            'controller_name' => 'ZEUS EX MACHINA : Inscription à la Newsletter'
        ]);
    }
}