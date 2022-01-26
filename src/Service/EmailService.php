<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class EmailService
{
    // PROPRIÉTÉ : A pour but de contenir quelque chose comme une valeur (int, float, string...) ou un objet.
    // L'objet ci-dessous est inaccessible depuis une fonction extérieure. On parle d'encapsulation.
    private $coeur;

    // Création du "constructeur", une méthode qui construit un objet paramétré et fonctionnel à partir de la classe.
    // C'est l'objet construit qui sera utilisé dans du code.
    public function __construct(MailerInterface $mailerInterface)
    {
        $this->coeur = $mailerInterface;
    }
    
    // MÉTHODE : A pour but de faire quelque chose (traitement) et retourner un résultat (sauf void).
    // À partir de l'objet construit, on crée une méthode d'envoi d'e-mail.
    public function envoyer(string $expediteur, string $destinataire, string $objet, string $path, array $variables): void
    {
        $email = (new TemplatedEmail())
            ->from($expediteur)
            ->to($destinataire)
            ->subject($objet)
            ->htmlTemplate($path)
            ->context($variables);
        
        $this->coeur->send($email);
    }
}