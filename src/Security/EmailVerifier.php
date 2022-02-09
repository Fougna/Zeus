<?php

namespace App\Security;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

//use Symfony\Component\HttpFoundation\Request;

class EmailVerifier
{
    private $verifyEmailHelper;
    private $mailer;
    private $entityManager;

    // Méthode "magique" utilisant les variables en mémoire de vérification (verifyEmailHelper),
    // d'envoi d'e-mail (mailer) et de sauvegarde des données créées (Entity Manager).
    public function __construct(VerifyEmailHelperInterface $helper, MailerInterface $mailer, EntityManagerInterface $manager)
    {
        $this->verifyEmailHelper = $helper;
        $this->mailer = $mailer;
        $this->entityManager = $manager;
    }

    // Méthode d'envoi d'e-mail de vérification/confirmation.
    public function sendEmailConfirmation(string $verifyEmailRouteName, UserInterface $user, TemplatedEmail $email): void
    {
        // Création d'une signature liée à l'e-mail de vérification et à l'utilisateur créé, grâce à son Id et son adresse e-mail.
        $signatureComponents = $this->verifyEmailHelper->generateSignature(
            $verifyEmailRouteName,
            $user->getId(),
            $user->getEmail()
        );

        // Création de l'e-mail de vérification.
        $context = $email->getContext();
        $context['signedUrl'] = $signatureComponents->getSignedUrl();
        $context['expiresAtMessageKey'] = $signatureComponents->getExpirationMessageKey();
        $context['expiresAtMessageData'] = $signatureComponents->getExpirationMessageData();

        // Enregistrement de l'e-mail de vérification dans une variable '$email'.
        $email->context($context);

        // Envoi de l'e-mail de vérification.
        $this->mailer->send($email);
    }

    // Méthode de validation de l'utilisateur si l'e-mail de vérification/confirmation est OK.
    /**
     * @throws VerifyEmailExceptionInterface
     */
    public function handleEmailConfirmation(Request $request, UserInterface $user): void
    {
        // Validation en récupérant les données de l'e-mail de confirmation(signature URL, id et e-mail de l'utilisateur).
        $this->verifyEmailHelper->validateEmailConfirmation($request->getUri(), $user->getId(), $user->getEmail());

        // Si la vérification est OK, on passe le statut 'isVerified' de l'utilisateur à 'true'. 
        $user->setIsVerified(true);

        // Sauvegarde des changements effectués grâce à l'Entity Manager.
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}