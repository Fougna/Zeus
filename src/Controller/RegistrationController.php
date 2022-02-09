<?php

namespace App\Controller;

use App\Entity\User;
use App\Security\EmailVerifier;
use App\Form\RegistrationFormType;
use Symfony\Component\Mime\Address;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    // Méthode "magique" d'e-mail de vérification.
    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }
    
    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, UserAuthenticatorInterface $userAuthenticator, LoginFormAuthenticator $authenticator): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            // Création d'une signature URL et envoi de l'e-mail de confirmation à l'utilisateur.
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                // Création du modèle de l'e-mail de confirmation.
                (new TemplatedEmail())
                    ->from(new Address('sebferran@gmail.com', 'ZEUS EX MACHINA'))
                    ->to($user->getEmail())
                    ->subject('Veuillez confirmer votre adresse e-mail')
                    ->htmlTemplate('registration/email_conf.html.twig')
            );

            // Vérification que l'utilisateur enregistré correspond bien à celui de l'e-mail de confirmation.
            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );

            // Retour vers la page d'accueil.
            return $this->redirectToRoute('home');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/verify/email", name="app_verify_email")
     */
    // Fonction de vérification de la confirmation de l'e-mail.
    public function verifyUserEmail(Request $request): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // Validation du lien de confirmation dans l'e-mail envoyé et modifie la colonne utilisateur 'isVerified' en 'true'.
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            // Affichage d'un message 'flash' temporaire d'échec de la validation.
            $this->addFlash('verify_email_error', $exception->getReason());

            // Redirection vers la page d'enregistrement.
            return $this->redirectToRoute('app_register');
        }

        // Affichage d'un message 'flash' temporaire de succès de la validation.
        $this->addFlash('success', 'Votre adresse e-mail a bien été vérifiéé !');

        // Redirection vers la page d'accueil.
        return $this->redirectToRoute('home');
    }
}