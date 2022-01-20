<?php

namespace App\Controller;

use App\Entity\Auteur;
use App\Form\AuteurType;
use App\Service\FileUploader;
use App\Repository\AuteurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/auteur")
 */
// Le commentaire ci-dessus précédé par un @ peut être lu par la machine et interprété.
// Ici, il indique à quelle route dans le navigateur correspond le cpntrôleur de l'entité ci-dessous.
class AdminAuteurController extends AbstractController
{
    /**
     * @Route("/", name="admin_auteur_index", methods={"GET"})
     */
    // Fonction d'affichage des entités "Auteur" enregistrées.
    public function index(AuteurRepository $auteurRepository): Response
    {
        // La méthode "render" permet de rendre le code PHP lisible à la vue.
        return $this->render('admin_auteur/index.html.twig', [
            // La méthode "findAll" va chercher tous les objets listés dans la répertoire "AuteurRepository".
            'auteurs' => $auteurRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin_auteur_new", methods={"GET", "POST"})
     */
    // Fonction de création d'une entité "Auteur".
    public function new(Request $request, FileUploader $fileUploader, EntityManagerInterface $entityManager): Response
    {
        // Assignation de l'objet "Auteur" dans une variable qu'on va pouvoir exploiter.
        $auteur = new Auteur();
        // Création d'un formulaire à partir des colonnes contenues dans l'objet "Auteur", également assigné dans une variable.
        $form = $this->createForm(AuteurType::class, $auteur);
        // Utilisation d'une méthode 'handleRequest' qui va lire les données rentrées par l'utilisateur (contenues dans la variable request), et les intégrer dans le formulaire.
        $form->handleRequest($request);
        // Condition qui vérifie si le formulaire a bien été envoyé et bien rempli.
        if ($form->isSubmitted() && $form->isValid()) {
            // On demande au formulaire d'aller chercher les données présentes dans la colonne 'image' qu'on assigne ensuite dans une nouvelle variable.
            $imageFile = $form->get('image')->getData();
            // Condition qui vérifie que la variable $imageFile contient bien des données récupérées.
            if ($imageFile)
            {
                // Grâce à FileUploader, on va chercher dans le dossier 'uploads' le fichier 'image' correspondant aux données trouvées.
                $imageFileName = $fileUploader->upload($imageFile);
                // On assigne le fichier 'image' trouvé dans l'objet "Auteur".
                $auteur->setImage($imageFileName);
            }
            // On demande à Doctrine de garder en mémoire l'objet "Auteur".
            $entityManager->persist($auteur);
            // On demande à Doctrine de sortir de la mémoire toutes les données qui ne sont plus utilisées avant une nouvelle requête.
            $entityManager->flush();
            // Redirection des données enregistrées en mémoire vers la page "admin_auteur_index".
            return $this->redirectToRoute('admin_auteur_index', [], Response::HTTP_SEE_OTHER);
        }
        // Conversion des données du formulaire dans la vue.
        return $this->renderForm('admin_auteur/new.html.twig', [
            'auteur' => $auteur,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="admin_auteur_show", methods={"GET"})
     */
    public function show(Auteur $auteur): Response
    {
        return $this->render('admin_auteur/show.html.twig', [
            'auteur' => $auteur,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_auteur_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, FileUploader $fileUploader, Auteur $auteur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AuteurType::class, $auteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();
            if ($imageFile)
            {
                $imageFileName = $fileUploader->upload($imageFile);
                $auteur->setImage($imageFileName);
            }
            
            $entityManager->flush();

            return $this->redirectToRoute('admin_auteur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_auteur/edit.html.twig', [
            'auteur' => $auteur,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="admin_auteur_delete", methods={"POST"})
     */
    public function delete(Request $request, Auteur $auteur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$auteur->getId(), $request->request->get('_token'))) {
            $entityManager->remove($auteur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_auteur_index', [], Response::HTTP_SEE_OTHER);
    }
}