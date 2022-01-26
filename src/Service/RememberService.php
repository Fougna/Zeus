<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

// Création d'un service qui va gérer la session utilisateur.
class RememberService
{
	private $coeur;

	// Méthode de construction de la session.
	public function __construct(SessionInterface $sessionInterface)
	{
		$this->coeur = $sessionInterface;
	}
	
	// Méthode qui va garder en mémoire les paramètres de la session.
	public function seSouvenir(string $nom, string $valeur): void
	{
		$this->coeur->set($nom, $valeur);
	}

	// Méthode qui va vider de la mémoire les données de la session.
	public function toutOublier(): void
	{
		$this->coeur->clear();
	}

	// Méthode qui va aller chercher dans la mémoire les données de la session.
	public function donneMoi(string $nom): ?string
	{
		return $this->coeur->get($nom);
	}
}