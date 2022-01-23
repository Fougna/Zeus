<?php

namespace App\Controller;

use App\Entity\Personnage;
use App\Repository\PersonnageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/personnage/catalog")
 */
class PersonnageCatalogController extends AbstractController
{
    /**
     * @Route("/", name="personnage_catalog_index", methods={"GET"})
     */
    public function index(PersonnageRepository $personnageRepository): Response
    {
        return $this->render('personnage_catalog/index.html.twig', [
            'personnages' => $personnageRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="personnage_catalog_show", methods={"GET"})
     */
    public function show(Personnage $personnage): Response
    {
        return $this->render('personnage_catalog/show.html.twig', [
            'personnage' => $personnage,
        ]);
    }
}