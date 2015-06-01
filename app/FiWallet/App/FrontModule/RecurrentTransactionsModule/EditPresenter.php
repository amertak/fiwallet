<?php

namespace FiWallet\App\FrontModule\RecurrentTransactionsModule;

use FiWallet\App\FrontModule\BasePresenter;
use FiWallet\App\FrontModule\RecurrentTransactionsModule\Forms\RecurrentTransactionFormFactory;
use FiWallet\Transactions\Recurrent\RecurrentTransactionsManager;
use Nette\Application\BadRequestException;
use Nette\Application\ForbiddenRequestException;
use Nette\Application\UI\Form;

/**
 * @author Pavel Kouřil <pavel.kouril@hotmail.com>
 */
class EditPresenter extends BasePresenter
{
    /**
     * @inject
     * @var RecurrentTransactionFormFactory
     */
    public $recurrentTransactionFormFactory;

    /**
     * @inject
     * @var RecurrentTransactionsManager
     */
    public $recurrentTransactionsManager;

    protected function createComponentRecTransactionForm()
    {
        $form = $this->recurrentTransactionFormFactory->create();
        $form->addHidden('id');
        $form->addCheckbox('active', 'Is active?');
        $form->addSubmit('send', 'Edit this recurrent transaction');
        $form->onSuccess[] = $this->recurrentTransactionFormFactory->editFormSubmitted;
        $form->onSuccess[] = function (Form $form) {
            $this->flashSuccess("Recurrent transaction details were successfully changed.");
            $this->redirect('List:');
        };
        return $form;
    }

    public function actionDefault($id)
    {
        if ($transaction = $this->recurrentTransactionsManager->find($id)) {
            if ($transaction->user->id != $this->user->id) {
                throw new ForbiddenRequestException;
            }
            $form = $this['recTransactionForm'];
            $form['name']->setDefaultValue($transaction->name);
            $form['first']->setDefaultValue($transaction->latestOccurrence->format('Y-m-d'));
            $form['amount']->setDefaultValue($transaction->amount);
            $form['interval']->setDefaultValue($transaction->occurenceInterval);
            $form['type']->setDefaultValue($transaction->type);
            $form['description']->setDefaultValue($transaction->description);
            $form['active']->setDefaultValue($transaction->isActive);
            $form['id']->setDefaultValue($transaction->id);
        } else {
            throw new BadRequestException;
        }
    }
}
