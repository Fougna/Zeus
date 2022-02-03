<?php

namespace App\Controller;

use App\Entity\CategoriePersonnage;
use App\Repository\CategoriePersonnageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/categorie/catalog")
 */
class CategorieCatalogController extends AbstractController
{
    /**
     * @Route("/", name="categorie_catalog_index", methods={"GET"})
     */
    public function index(CategoriePersonnageRepository $categoriePersonnageRepository): Response
    {
        return $this->render('categorie_catalog/index.html.twig', [
            'categorie_personnages' => $categoriePersonnageRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="categorie_catalog_show", methods={"GET"})
     */
    public function show(CategoriePersonnage $categoriePersonnage): Response
    {
        return $this->render('categorie_catalog/show.html.twig', [
            'categorie_personnage' => $categoriePersonnage,
        ]);
    }
}