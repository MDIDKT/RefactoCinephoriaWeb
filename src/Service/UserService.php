<?php

namespace App\Service;

use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService
{
    public array $getUsersByRole;

    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {
    }

    // Liste de tous les utilisateurs
    public function getAllUsers(): array
    {
        return $this->userRepository->findAllUsers();
    }

    // recuperer un utilisateur par son ID
    public function getUserById(int $id): ?object
    {
        return $this->userRepository->find($id);
    }

    // Ajouter un nouvel utilisateur
    public function createUser($user): void
    {
        $hashedPassword = $this->passwordHasher->hashPassword($user, $user->getPlainPassword());
        $user->setPassword($hashedPassword);
        $this->userRepository->save($user);
    }

    // Supprimer un utilisateur

    public function saveUser($user): void
    {
        $hashedPassword = $this->passwordHasher->hashPassword($user, $user->getPlainPassword());
        $user->setPassword($hashedPassword);
        $this->userRepository->save($user);
    }

    // Mettre à jour le mot de passe d'un utilisateur

    public function deleteUser($user): void
    {
        $this->userRepository->delete($user);
    }

    // Mot de passe oublié

    public function updatePassword($user, string $newPassword): void
    {
        $hashedPassword = $this->passwordHasher->hashPassword($user, $newPassword);
        $user->setPassword($hashedPassword);
        $this->userRepository->save($user);
    }

    public function resetPassword($user, string $newPassword): void
    {
        $hashedPassword = $this->passwordHasher->hashPassword($user, $newPassword);
        $user->setPassword($hashedPassword);
        $this->userRepository->save($user);
    }
}
