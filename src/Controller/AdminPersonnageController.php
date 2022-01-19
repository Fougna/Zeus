<?php

namespace App\Controller;

use App\Entity\Personnage;
use App\Form\PersonnageType;
use App\Service\FileUploader;
use App\Repository\PersonnageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/personnage")
 */
class AdminPersonnageController extends AbstractController
{
    /**
     * @Route("/", name="admin_personnage_index", methods={"GET"})
     */
    public function index(PersonnageRepository $personnageRepository): Response
    {
        return $this->render('admin_personnage/index.html.twig', [
            'personnages' => $personnageRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin_personnage_new", methods={"GET", "POST"})
     */
    public function new(Request $request, FileUploader $fileUploader, EntityManagerInterface $entityManager): Response
    {
        $personnage = new Personnage();
        $form = $this->createForm(PersonnageType::class, $personnage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            if ($imageFile)
            {
                $imageFileName = $fileUploader->upload($imageFile);
                $personnage->setImage($imageFileName);
            }
            
            $entityManager->persist($personnage);
            $entityManager->flush();

            return $this->redirectToRoute('admin_personnage_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_personnage/new.html.twig', [
            'personnage' => $personnage,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="admin_personnage_show", methods={"GET"})
     */
    public function show(Personnage $personnage): Response
    {
        return $this->render('admin_personnage/show.html.twig', [
            'personnage' => $personnage,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_personnage_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, FileUploader $fileUploader, Personnage $personnage, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PersonnageType::class, $personnage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            if ($imageFile)
            {
                $imageFileName = $fileUploader->upload($imageFile);
                $personnage->setImage($imageFileName);
            }

            $entityManager->flush();

            return $this->redirectToRoute('admin_personnage_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_personnage/edit.html.twig', [
            'personnage' => $personnage,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="admin_personnage_delete", methods={"POST"})
     */
    public function delete(Request $request, Personnage $personnage, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$personnage->getId(), $request->request->get('_token'))) {
            $entityManager->remove($personnage);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_personnage_index', [], Response::HTTP_SEE_OTHER);
    }
}
