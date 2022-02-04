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
    public function index(Request $request, LegendeRepository $legendeRepository): Response
    {
        // Après avoir injecté la dépendance 'Request', les requêtes HTTP deviennent accessibles.
        // On procède à une requête HTTP qui va aller chercher dans la navigation la valeur correspond à 'search'.
        // On assigne le résultat de la requête dans une variable '$search'.
        $search = $request->query->get('search');
        
        // Condition qui vérifie que la variable '$search' contient bien quelque chose.
        // Si elle contient une valeur, on va chercher dans le répertoire la ou les entrées correspondant à la requête '$search'.
        // Puis, on assigne les recherches trouvées dans une variable '$legendes'.
        if ($search)
        {
            $legendes = $legendeRepository->findBySearch($search);
        // S'il n'y a rien dans la variable '$search', on affiche tous les éléments en base de données.
        } else {
            $legendes = $legendeRepository->findAll();
        }

        // On envoie le résultat de la variable '$legendes' dans la vue.
        return $this->render('legend_catalog/index.html.twig', [
            'legendes' => $legendes
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