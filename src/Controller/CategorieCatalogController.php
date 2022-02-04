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
    public function index(Request $request, CategoriePersonnageRepository $categoriePersonnageRepository): Response
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
            $categoriePersonnages = $categoriePersonnageRepository->findBySearch($search);
        // S'il n'y a rien dans la variable '$search', on affiche tous les éléments en base de données.
        } else {
            $categoriePersonnages = $categoriePersonnageRepository->findAll();
        }

        // On envoie le résultat de la variable '$personnages' dans la vue.
        return $this->render('categorie_catalog/index.html.twig', [
            'categorie_personnages' => $categoriePersonnages,
        ]);
    }

    /**
     * @Route("/{id}", name="categorie_catalog_show", methods={"GET"})
     */
    public function show(CategoriePersonnage $categoriePersonnage): Response
    {
        return $this->render('categorie_catalog/show.html.twig', [
            'categorie_personnage' => $categoriePersonnages,
        ]);
    }
}