<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/article/catalog")
 */
class ArticleCatalogController extends AbstractController
{
    /**
     * @Route("/", name="article_catalog_index", methods={"GET"})
     */
    public function index(ArticleRepository $articleRepository): Response
    {
        return $this->render('article_catalog/index.html.twig', [
            'articles' => $articleRepository->findAll(),
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