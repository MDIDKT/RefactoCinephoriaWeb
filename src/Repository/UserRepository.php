<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    // liste de tous les utilisateurs
    public function findAllUsers(): array
    {
        return $this->findBy([], ['nom' => 'ASC']);
    }

    // Utilisateur par son ID
    public function findUserById(int $id): ?User
    {
        return $this->find($id);
    }

    // nouvelle utilisateur
    public function save(User $user): void
    {
        $this->_em->persist($user);
        $this->_em->flush();
    }

    // supprimer un utilisateur
    public function delete(User $user): void
    {
        $this->_em->remove($user);
        $this->_em->flush();
    }

    // mot de passe oublié
    public function findUserByEmail(string $email): ?User
    {
        return $this->findOneBy(['email' => $email]);
    }

    //modifier un utilisateur
    public function update(User $user): void
    {
        $this->_em->persist($user);
        $this->_em->flush();
    }
}
