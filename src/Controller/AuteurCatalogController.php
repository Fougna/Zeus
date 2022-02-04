<?php

namespace App\Controller;

use App\Entity\Auteur;
use App\Repository\AuteurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/auteur/catalog")
 */
class AuteurCatalogController extends AbstractController
{
    /**
     * @Route("/", name="auteur_catalog_index", methods={"GET"})
     */
    public function index(Request $request, AuteurRepository $auteurRepository): Response
    {
        // Après avoir injecté la dépendance 'Request', les requêtes HTTP deviennent accessibles.
        // On procède à une requête HTTP qui va aller chercher dans la navigation la valeur correspond à 'search'.
        // On assigne le résultat de la requête dans une variable '$search'.
        $search = $request->query->get('search');
        
        // Condition qui vérifie que la variable '$search' contient bien quelque chose.
        // Si elle contient une valeur, on va chercher dans le répertoire la ou les entrées correspondant à la requête '$search'.
        // Puis, on assigne les recherches trouvées dans une variable '$auteurs'.
        if ($search)
        {
            $auteurs = $auteurRepository->findBySearch($search);
        // S'il n'y a rien dans la variable '$search', on affiche tous les éléments en base de données.
        } else {
            $auteurs = $auteurRepository->findAll();
        }

        // On envoie le résultat de la variable '$auteurs' dans la vue.
        return $this->render('auteur_catalog/index.html.twig', [
            'auteurs' => $auteurs
        ]);
    }

    /**
     * @Route("/{id}", name="auteur_catalog_show", methods={"GET"})
     */
    public function show(Auteur $auteur): Response
    {
        return $this->render('auteur_catalog/show.html.twig', [
            'auteur' => $auteur,
        ]);
    }
}