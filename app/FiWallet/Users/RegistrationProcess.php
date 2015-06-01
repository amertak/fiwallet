<?php

namespace FiWallet\Users;

use Doctrine\DBAL\DBALException;
use FiWallet\Accounts\Account;
use FiWallet\Notifications\NotificationsManager;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Doctrine\EntityRepository;
use Nette\Object;

/**
 * @author Pavel Kouřil <pavel.kouril@hotmail.com>
 */
class RegistrationProcess extends Object
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var EntityRepository
     */
    private $userRepository;
    /**
     * @var NotificationsManager
     */
    private $notificationsManager;

    public function __construct(EntityManager $entityManager, NotificationsManager $notificationsManager)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $entityManager->getRepository(User::class);
        $this->notificationsManager = $notificationsManager;
    }

    /**
     * @param string $email
     * @param string $username
     * @param string $fullName
     * @param string $password
     *
     * @return User|null
     */
    public function register($email, $username, $fullName, $password)
    {
        if ($user = $this->userRepository->findOneBy(['username' => $username])) {
            return null;
        }
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $user = new User($username, $hashedPassword, $email, $fullName);
        $this->entityManager->persist($user);
        $defaultAccount = new Account($user, 'Cash', 'CZK');
        $this->entityManager->persist($defaultAccount);
        try {
            $this->entityManager->flush();
            $this->notificationsManager->addTextNotification($user, 'Welcome!');
            return $user;
        } catch (DBALException $e) {
            return null;
        }
    }
}
