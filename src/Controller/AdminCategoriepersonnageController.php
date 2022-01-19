<?php

namespace App\Controller;

use App\Service\FileUploader;
use App\Entity\CategoriePersonnage;
use App\Form\CategoriePersonnageType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\CategoriePersonnageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/categoriepersonnage")
 */
class AdminCategoriepersonnageController extends AbstractController
{
    /**
     * @Route("/", name="admin_categoriepersonnage_index", methods={"GET"})
     */
    public function index(CategoriePersonnageRepository $categoriePersonnageRepository): Response
    {
        return $this->render('admin_categoriepersonnage/index.html.twig', [
            'categorie_personnages' => $categoriePersonnageRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin_categoriepersonnage_new", methods={"GET", "POST"})
     */
    public function new(Request $request, FileUploader $fileUploader, EntityManagerInterface $entityManager): Response
    {
        $categoriePersonnage = new CategoriePersonnage();
        $form = $this->createForm(CategoriePersonnageType::class, $categoriePersonnage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            if ($imageFile)
            {
                $imageFileName = $fileUploader->upload($imageFile);
                $categoriePersonnage->setImage($imageFileName);
            }

            $entityManager->persist($categoriePersonnage);
            $entityManager->flush();

            return $this->redirectToRoute('admin_categoriepersonnage_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_categoriepersonnage/new.html.twig', [
            'categorie_personnage' => $categoriePersonnage,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="admin_categoriepersonnage_show", methods={"GET"})
     */
    public function show(CategoriePersonnage $categoriePersonnage): Response
    {
        return $this->render('admin_categoriepersonnage/show.html.twig', [
            'categorie_personnage' => $categoriePersonnage,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_categoriepersonnage_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, FileUploader $fileUploader, CategoriePersonnage $categoriePersonnage, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CategoriePersonnageType::class, $categoriePersonnage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            if ($imageFile)
            {
                $imageFileName = $fileUploader->upload($imageFile);
                $categoriePersonnage->setImage($imageFileName);
            }
            
            $entityManager->flush();

            return $this->redirectToRoute('admin_categoriepersonnage_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_categoriepersonnage/edit.html.twig', [
            'categorie_personnage' => $categoriePersonnage,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="admin_categoriepersonnage_delete", methods={"POST"})
     */
    public function delete(Request $request, CategoriePersonnage $categoriePersonnage, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categoriePersonnage->getId(), $request->request->get('_token'))) {
            $entityManager->remove($categoriePersonnage);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_categoriepersonnage_index', [], Response::HTTP_SEE_OTHER);
    }
}
