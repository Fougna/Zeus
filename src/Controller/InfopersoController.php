<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InfopersoController extends AbstractController
{
    /**
     * @Route("/infoperso", name="infoperso")
     */
    public function index(): Response
    {
        return $this->render('infoperso/index.html.twig', [
            'controller_name' => 'InfopersoController',
        ]);
    }
}
