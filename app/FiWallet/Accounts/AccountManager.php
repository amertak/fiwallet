<?php

namespace FiWallet\Accounts;

use Kdyby\Doctrine\EntityRepository;
use Nette\Object;
use Kdyby\Doctrine\EntityManager;
use FiWallet\Users\User;

/**
 * @author Pavel Kouřil <pavel.kouril@hotmail.com>
 */
class AccountManager extends Object
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var EntityRepository
     */
    private $accountRepository;

    public function __construct(EntityManager $entityManager)
    {
        $this->accountRepository = $entityManager->getRepository(Account::class);
        $this->entityManager = $entityManager;
    }

    /**
     * @param int $id
     * @return Account|null
     */
    public function find($id)
    {
        return $this->accountRepository->find($id);
    }

    /**
     * @param User $user
     * @param string $name
     * @param string $currency
     * @param float $balance
     *
     * @return Account
     */
    public function createAccount(User $user, $name, $currency, $balance)
    {
        $account = new Account($user, $name, $currency, $balance);
        $this->entityManager->persist($account);
        $this->entityManager->flush($account);
        return $account;
    }

    /**
     * @param Account $account
     * @param string $name
     * @param float $balance
     */
    public function editAccount(Account $account, $name, $balance)
    {
        $account->name = $name;
        $account->balance = $balance;
        $this->entityManager->flush($account);
    }
}
