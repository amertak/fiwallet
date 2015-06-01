<?php

namespace FiWallet\App\FrontModule\UsersModule;

use FiWallet\Users\UserManager;
use FiWallet\App\FrontModule\UsersModule\Forms\UserFormFactory;
use FiWallet\App\FrontModule\BasePresenter;
use Nette\Application\BadRequestException;
use Nette\Application\UI\Form;

/**
 * @author Adam Studenic
 */
class EditPresenter extends BasePresenter
{
    /**
     * @inject
     * @var UserFormFactory
     */
    public $userFormFactory;

    /**
     * @inject
     * @var UserManager
     */
    public $userManager;

    protected function createComponentUserForm()
    {
        $form = $this->userFormFactory->create();
        $form->addSubmit('send', 'Edit user');
        $form->onSuccess[] = $this->userFormFactory->editFormSubmitted;
        $form->onSuccess[] = function (Form $form) {
            $this->flashSuccess("User successfully edited.");
            $this->redirect('Edit:', array("id" => $this->user->id));
        };
        return $form;
    }

    public function actionDefault()
    {
        if ($user = $this->userManager->find($this->user->id)) {
            $form = $this['userForm'];
            $form['username']->setDefaultValue($user->username);
            $form['email']->setDefaultValue($user->email);
            $form['fullName']->setDefaultValue($user->fullName);
        } else {
            throw new BadRequestException;
        }
    }
}
