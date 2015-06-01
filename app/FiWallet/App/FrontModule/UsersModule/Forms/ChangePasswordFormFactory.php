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
 * @property-read callable $saveFormSubmitted (Form) : void
 */
class ChangePasswordFormFactory extends Object implements IFormFactory
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
        $form->addHidden('id');
        $form->addPassword('oldPassword', 'Old password')->setRequired();
        $form->addPassword('password', 'New password')->setRequired();
        $form->addPassword('password2', 'New password (again)')->setRequired()->addRule($form::EQUAL, 'Passwords must match!', $form['password']);
        return $form;
    }
    
    /**
     * @param Form $form
     */
    public function saveFormSubmitted(Form $form)
    {
        $values = $form->values;

        if(password_verify($form->values->oldPassword, $this->user->getIdentity()->password)) {
            $User = $this->UserManager->find($this->user->getIdentity()->getId());

            if($values->password != $values->password2){
                $form->addError("Repeated password does not match.");
            } else {
                $this->UserManager->changePasswordUser($User, $values->password);
            }

        } else {
            $form->addError("Your existing password is not correct.");
        }


    }
}
