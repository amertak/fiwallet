<?php

namespace FiWallet\Users;

use Kdyby\Doctrine\EntityRepository;
use Nette\Object;
use Kdyby\Doctrine\EntityManager;
use FiWallet\Users\User;

/**
 * @author Adam Studenic>
 */
class UserManager extends Object
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var EntityRepository
     */
    private $userRepository;

    public function __construct(EntityManager $entityManager)
    {
        $this->userRepository = $entityManager->getRepository(User::class);
        $this->entityManager = $entityManager;
    }

    /**
     * @param int $id
     * @return User|null
     */
    public function find($id)
    {
        return $this->userRepository->find($id);
    }


    /**
     * @param User $user
     * @param string $email
     * @param string $fullName
     */
    public function editUser(User $user, $email, $fullName )
    {
        $user->email = $email;
        $user->fullName = $fullName;
        $this->entityManager->flush($user);
    }


    /**
     * @param User $user
     * @param string $password
     */
    public function changePasswordUser(User $user, $password )
    {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $user->password = $hashedPassword;
        $this->entityManager->flush($user);
    }

}
