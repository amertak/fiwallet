<?php

namespace FiWallet\App\FrontModule\AccountsModule\Forms;

use FiWallet\Accounts\AccountManager;
use FiWallet\App\FrontModule\Components\IFormFactory;
use Nette\Application\UI\Form;
use Nette\Object;
use Nette\Security\User as NetteUser;

/**
 * @author Pavel Kouřil <pavel.kouril@hotmail.com>
 *
 * @property-read callable $addFormSubmitted (Form) : void
 * @property-read callable $editFormSubmitted (Form) : void
 */
class AccountFormFactory extends Object implements IFormFactory
{
    /**
     * @var AccountManager
     */
    private $accountManager;

    /**
     * @var NetteUser
     */
    private $user;

    public function __construct(NetteUser $user, AccountManager $accountManager)
    {
        $this->accountManager = $accountManager;
        $this->user = $user;
    }

    public function create()
    {
        $form = new Form();
        $form->addHidden('id');
        $form->addText('name', 'Name')->setRequired();
        $form->addSelect('currency', 'Currency', ['CZK' => 'Kč (CZK)', 'USD' => '$ (USD)', 'EUR' => '€ (EUR)']);
        $form->addText('balance', 'Starting balance')->setDefaultValue(0)->setRequired()->addRule($form::FLOAT, 'Balance has to be a number');
        return $form;
    }

    /**
     * @param Form $form
     */
    public function addFormSubmitted(Form $form)
    {
        $values = $form->values;
        $this->accountManager->createAccount($this->user->identity, $values->name, $values->currency, $values->balance);
    }

    /**
     * @param Form $form
     */
    public function editFormSubmitted(Form $form)
    {
        $values = $form->values;
        if ($account = $this->accountManager->find($values->id)) {
            $this->accountManager->editAccount($account, $values->name, $values->balance);
        } else {
            $form->addError("Account you are trying to edit doesn't exist.");
        }
    }
}
