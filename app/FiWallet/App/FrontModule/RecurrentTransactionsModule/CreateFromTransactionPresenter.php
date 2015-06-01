<?php

namespace FiWallet\App\FrontModule\RecurrentTransactionsModule;

use FiWallet\App\FrontModule\BasePresenter;
use FiWallet\App\FrontModule\RecurrentTransactionsModule\Forms\RecurrentTransactionFormFactory;
use FiWallet\Transactions\TransactionsManager;
use Nette\Application\BadRequestException;
use Nette\Application\ForbiddenRequestException;
use Nette\Application\UI\Form;

/**
 * @author Pavel Kouřil <pavel.kouril@hotmail.com>
 */
class CreateFromTransactionPresenter extends BasePresenter
{
    /**
     * @inject
     * @var RecurrentTransactionFormFactory
     */
    public $recurrentTransactionFormFactory;

    /**
     * @inject
     * @var TransactionsManager
     */
    public $transactionsManager;

    protected function createComponentRecTransactionForm()
    {
        $form = $this->recurrentTransactionFormFactory->create();
        $form->addHidden('fromTransaction');
        $form->addSubmit('send', 'Create new recurrent transaction');
        $form->onSuccess[] = $this->recurrentTransactionFormFactory->addFormSubmitted;
        $form->onSuccess[] = function (Form $form) {
            $this->flashSuccess("New recurrent transaction added. Now you just have to wait until the recurrent transaction is supposed to happen.");
            $this->redirect('List:');
        };
        return $form;
    }

    public function actionDefault($id)
    {
        if ($transaction = $this->transactionsManager->find($id)) {
            if ($transaction->user->id != $this->user->id) {
                throw new ForbiddenRequestException;
            }
            $form = $this['recTransactionForm'];
            $form['name']->setDefaultValue($transaction->name);
            $form['first']->setDefaultValue($transaction->dateOfTransaction->format('Y-m-d'));
            $form['amount']->setDefaultValue($transaction->amount);
            $form['fromTransaction']->setDefaultValue($transaction->id);
        } else {
            throw new BadRequestException;
        }
    }
}
