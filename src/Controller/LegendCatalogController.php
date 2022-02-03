<?php

namespace App\Controller;

use App\Entity\Legende;
use App\Repository\LegendeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/legend/catalog")
 */
class LegendCatalogController extends AbstractController
{
    /**
     * @Route("/", name="legend_catalog_index", methods={"GET"})
     */
    public function index(LegendeRepository $legendeRepository): Response
    {
        return $this->render('legend_catalog/index.html.twig', [
            'legendes' => $legendeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="legend_catalog_show", methods={"GET"})
     */
    public function show(Legende $legende): Response
    {
        return $this->render('legend_catalog/show.html.twig', [
            'legende' => $legende,
        ]);
    }
}