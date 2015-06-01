<?php

namespace FiWallet\Users;

use Kdyby\Doctrine\EntityRepository;
use Nette\Security\AuthenticationException;
use Nette\Security\IAuthenticator;
use Kdyby\Doctrine\EntityManager;

/**
 * @author Pavel Kouřil <pavel.kouril@hotmail.com>
 */
class Authentizator implements IAuthenticator
{
    /**
     * @var EntityRepository
     */
    private $userRepository;

    public function __construct(EntityManager $entityManager)
    {
        $this->userRepository = $entityManager->getRepository(User::class);
    }

    public function authenticate(array $credentials)
    {
        list ($username, $password) = $credentials;
        /** @var User $user */
        if ($user = $this->userRepository->findOneBy(['username' => $username])) {
            if (password_verify($password, $user->password)) {
                return $user;
            } else {
                throw new AuthenticationException("Wrong password.");
            }
        } else {
            throw new AuthenticationException("User does not exist.");
        }
    }
}
