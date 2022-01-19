<?php

namespace App\Controller;

use App\Entity\Legende;
use App\Form\LegendeType;
use App\Service\FileUploader;
use App\Repository\LegendeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/legende")
 */
class AdminLegendeController extends AbstractController
{
    /**
     * @Route("/", name="admin_legende_index", methods={"GET"})
     */
    public function index(LegendeRepository $legendeRepository): Response
    {
        return $this->render('admin_legende/index.html.twig', [
            'legendes' => $legendeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin_legende_new", methods={"GET", "POST"})
     */
    public function new(Request $request, FileUploader $fileUploader, EntityManagerInterface $entityManager): Response
    {
        $legende = new Legende();
        $form = $this->createForm(LegendeType::class, $legende);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            if ($imageFile)
            {
                $imageFileName = $fileUploader->upload($imageFile);
                $legende->setImage($imageFileName);
            }
            
            $entityManager->persist($legende);
            $entityManager->flush();

            return $this->redirectToRoute('admin_legende_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_legende/new.html.twig', [
            'legende' => $legende,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="admin_legende_show", methods={"GET"})
     */
    public function show(Legende $legende): Response
    {
        return $this->render('admin_legende/show.html.twig', [
            'legende' => $legende,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_legende_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, FileUploader $fileUploader, Legende $legende, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LegendeType::class, $legende);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            if ($imageFile)
            {
                $imageFileName = $fileUploader->upload($imageFile);
                $legende->setImage($imageFileName);
            }

            $entityManager->flush();

            return $this->redirectToRoute('admin_legende_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_legende/edit.html.twig', [
            'legende' => $legende,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="admin_legende_delete", methods={"POST"})
     */
    public function delete(Request $request, Legende $legende, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$legende->getId(), $request->request->get('_token'))) {
            $entityManager->remove($legende);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_legende_index', [], Response::HTTP_SEE_OTHER);
    }
}
