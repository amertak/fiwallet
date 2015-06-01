<?php

namespace FiWallet\App\FrontModule\AccountsModule;

use FiWallet\Accounts\AccountManager;
use FiWallet\App\FrontModule\AccountsModule\Forms\AccountFormFactory;
use FiWallet\App\FrontModule\BasePresenter;
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
     * @var AccountFormFactory
     */
    public $accountFormFactory;

    /**
     * @inject
     * @var AccountManager
     */
    public $accountManager;

    protected function createComponentAccountForm()
    {
        $form = $this->accountFormFactory->create();
        $form->addSubmit('send', 'Edit account');
        $form->onSuccess[] = $this->accountFormFactory->editFormSubmitted;
        $form->onSuccess[] = function (Form $form) {
            $this->flashSuccess("Account successfully edited.");
            $this->redirect('List:');
        };
        return $form;
    }

    public function actionDefault($id)
    {
        if ($account = $this->accountManager->find($id)) {
            if ($account->user->id != $this->user->id) {
                throw new ForbiddenRequestException;
            }
            $form = $this['accountForm'];
            $form['id']->setDefaultValue($id);
            $form['name']->setDefaultValue($account->name);
            $form['balance']->setDefaultValue($account->balance);
            $form['currency']->setDisabled()->setDefaultValue($account->currency);
        } else {
            throw new BadRequestException;
        }
    }
}
