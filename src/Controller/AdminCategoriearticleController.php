<?php

namespace App\Controller;

use App\Entity\CategorieArticle;
use App\Form\CategorieArticleType;
use App\Repository\CategorieArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/categoriearticle")
 */
class AdminCategoriearticleController extends AbstractController
{
    /**
     * @Route("/", name="admin_categoriearticle_index", methods={"GET"})
     */
    public function index(CategorieArticleRepository $categorieArticleRepository): Response
    {
        return $this->render('admin_categoriearticle/index.html.twig', [
            'categorie_articles' => $categorieArticleRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin_categoriearticle_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $categorieArticle = new CategorieArticle();
        $form = $this->createForm(CategorieArticleType::class, $categorieArticle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($categorieArticle);
            $entityManager->flush();

            return $this->redirectToRoute('admin_categoriearticle_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_categoriearticle/new.html.twig', [
            'categorie_article' => $categorieArticle,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="admin_categoriearticle_show", methods={"GET"})
     */
    public function show(CategorieArticle $categorieArticle): Response
    {
        return $this->render('admin_categoriearticle/show.html.twig', [
            'categorie_article' => $categorieArticle,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_categoriearticle_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, CategorieArticle $categorieArticle, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategorieArticleType::class, $categorieArticle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('admin_categoriearticle_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_categoriearticle/edit.html.twig', [
            'categorie_article' => $categorieArticle,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="admin_categoriearticle_delete", methods={"POST"})
     */
    public function delete(Request $request, CategorieArticle $categorieArticle, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categorieArticle->getId(), $request->request->get('_token'))) {
            $entityManager->remove($categorieArticle);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_categoriearticle_index', [], Response::HTTP_SEE_OTHER);
    }
}
