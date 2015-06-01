<?php

namespace FiWallet\App\FrontModule\AccountsModule;

use FiWallet\App\FrontModule\AccountsModule\Forms\AccountFormFactory;
use FiWallet\App\FrontModule\BasePresenter;
use Nette\Application\UI\Form;

/**
 * @author Pavel Kouřil <pavel.kouril@hotmail.com>
 */
class AddPresenter extends BasePresenter
{
    /**
     * @inject
     * @var AccountFormFactory
     */
    public $accountFormFactory;

    protected function createComponentAccountForm()
    {
        $form = $this->accountFormFactory->create();
        $form->addSubmit('send', 'Create new account');
        $form->onSuccess[] = $this->accountFormFactory->addFormSubmitted;
        $form->onSuccess[] = function (Form $form) {
            $this->flashSuccess("New account succesfully created! Why don't you go to dashboard and add some transactions?");
            $this->redirect('List:');
        };
        return $form;
    }
}
