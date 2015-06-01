<?php

namespace FiWallet\App\FrontModule\AccountsModule;

use FiWallet\Accounts\Account;
use FiWallet\App\FrontModule\BasePresenter;
use Kdyby\Doctrine\EntityRepository;

/**
 * @author Pavel Kouřil <pavel.kouril@hotmail.com>
 */
class ListPresenter extends BasePresenter
{
    /**
     * @var EntityRepository
     */
    private $accountsRepository;

    protected function startup()
    {
        parent::startup();
        $this->accountsRepository = $this->entityManager->getRepository(Account::class);
    }

    public function actionDefault()
    {
        $this->template->accounts = $this->accountsRepository->findBy(['user' => $this->user->identity]);
    }
}
