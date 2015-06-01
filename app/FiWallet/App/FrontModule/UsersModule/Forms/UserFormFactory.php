<?php

namespace FiWallet\App\FrontModule\UsersModule\Forms;

use FiWallet\Users\UserManager;
use FiWallet\App\FrontModule\Components\IFormFactory;
use Nette\Application\UI\Form;
use Nette\Object;
use Nette\Security\User as NetteUser;

/**
 * @author Adam Studenic
 *
 * @property-read callable $editFormSubmitted (Form) : void
 */
class UserFormFactory extends Object implements IFormFactory
{
    /**
     * @var UserManager
     */
    private $UserManager;

    /**
     * @var NetteUser
     */
    private $user;

    public function __construct(NetteUser $user, UserManager $UserManager)
    {
        $this->UserManager = $UserManager;
        $this->user = $user;
    }

    public function create()
    {
        $form = new Form();
        $form->addText('username', 'Username (login)')->setRequired()->controlPrototype->readonly='readonly';
        $form->addText('email', 'Email')->setRequired();
        $form->addText('fullName', 'Full name')->setRequired();
        return $form;
    }

    /**
     * @param Form $form
     */
    public function editFormSubmitted(Form $form)
    {
        $values = $form->values;
        if ($User = $this->UserManager->find($this->user->id)) {
            $this->UserManager->editUser($User, $values->email, $values->fullName);
        } else {
            $form->addError("User you are trying to edit doesn't exist.");
        }
    }
}
