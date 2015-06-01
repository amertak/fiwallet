<?php

namespace FiWallet\App\FrontModule\RecurrentTransactionsModule;

use FiWallet\App\FrontModule\BasePresenter;
use FiWallet\Transactions\Recurrent\RecurrentTransaction;
use Kdyby\Doctrine\EntityRepository;

/**
 * @author Pavel Kouřil <pavel.kouril@hotmail.com>
 */
class ListPresenter extends BasePresenter
{
    /**
     * @var EntityRepository
     */
    private $recurrentTransactionsRepository;

    protected function startup()
    {
        parent::startup();
        $this->recurrentTransactionsRepository = $this->entityManager->getRepository(RecurrentTransaction::class);
    }

    public function actionDefault()
    {
        $this->template->recTransactions = $this->recurrentTransactionsRepository->findBy(['user' => $this->user->identity]);
    }

    public function handleToggleActive($id)
    {

    }
}
