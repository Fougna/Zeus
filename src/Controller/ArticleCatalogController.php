<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/article/catalog")
 */
class ArticleCatalogController extends AbstractController
{
    /**
     * @Route("/", name="article_catalog_index", methods={"GET"})
     */
    public function index(Request $request, ArticleRepository $articleRepository): Response
    {
        // Après avoir injecté la dépendance 'Request', les requêtes HTTP deviennent accessibles.
        // On procède à une requête HTTP qui va aller chercher dans la navigation la valeur correspond à 'search'.
        // On assigne le résultat de la requête dans une variable '$search'.
        $search = $request->query->get('search');
        
        // Condition qui vérifie que la variable '$search' contient bien quelque chose.
        // Si elle contient une valeur, on va chercher dans le répertoire la ou les entrées correspondant à la requête '$search'.
        // Puis, on assigne les recherches trouvées dans une variable '$articles'.
        if ($search)
        {
            $articles = $articleRepository->findBySearch($search);
        // S'il n'y a rien dans la variable '$search', on affiche tous les éléments en base de données.
        } else {
            $articles = $articleRepository->findAll();
        }

        // On envoie le résultat de la variable '$articles' dans la vue.
        return $this->render('article_catalog/index.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/{id}", name="article_catalog_show", methods={"GET"})
     */
    public function show(Article $article): Response
    {
        return $this->render('article_catalog/show.html.twig', [
            'article' => $article,
        ]);
    }
}