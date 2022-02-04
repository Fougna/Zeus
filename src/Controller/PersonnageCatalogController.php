<?php

namespace App\Controller;

use App\Entity\Personnage;
use App\Repository\PersonnageRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/personnage/catalog")
 */
class PersonnageCatalogController extends AbstractController
{
    /**
     * @Route("/", name="personnage_catalog_index", methods={"GET"})
     */
    public function index(Request $request, PersonnageRepository $personnageRepository): Response
    {
        // Après avoir injecté la dépendance 'Request', les requêtes HTTP deviennent accessibles.
        // On procède à une requête HTTP qui va aller chercher dans la navigation la valeur correspond à 'search'.
        // On assigne le résultat de la requête dans une variable '$search'.
        $search = $request->query->get('search');
        
        // Condition qui vérifie que la variable '$search' contient bien quelque chose.
        // Si elle contient une valeur, on va chercher dans le répertoire la ou les entrées correspondant à la requête '$search'.
        // Puis, on assigne les recherches trouvées dans une variable '$personnages'.
        if ($search)
        {
            $personnages = $personnageRepository->findBySearch($search);
        // S'il n'y a rien dans la variable '$search', on affiche tous les éléments en base de données.
        } else {
            $personnages = $personnageRepository->findAll();
        }

        // On envoie le résultat de la variable '$personnages' dans la vue.
        return $this->render('personnage_catalog/index.html.twig', [
            'personnages' => $personnages
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