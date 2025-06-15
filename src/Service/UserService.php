<?php

namespace App\Service;

use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;

class UserService
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {}

    public function creerUtilisateur(array $data): User
    {
        // Crée un nouvel utilisateur, hash le mdp, gère rôles
    }

    public function hashPassword(User $user, string $plainPassword): string
    {
        // Retourne un mot de passe hashé
    }

    public function envoyerMailConfirmation(User $user): void
    {
        // Envoie l’email de confirmation d’inscription
    }

    public function reinitialiserMdp(User $user): void
    {
        // Gère la procédure de reset du mot de passe
    }
}
