<?php

namespace FiWallet\App\FrontModule\UsersModule;

use FiWallet\Users\UserManager;
use FiWallet\App\FrontModule\UsersModule\Forms\ChangePasswordFormFactory;
use FiWallet\App\FrontModule\BasePresenter;
use Nette\Application\BadRequestException;
use Nette\Application\ForbiddenRequestException;
use Nette\Application\UI\Form;

/**
 * @author Adam Studenic
 */
class ChangePasswordPresenter extends BasePresenter
{
    /**
     * @inject
     * @var ChangePasswordFormFactory
     */
    public $changePasswordFormFactory;

    /**
     * @inject
     * @var UserManager
     */
    public $userManager;

    protected function createComponentChangePasswordForm()
    {
        $form = $this->changePasswordFormFactory->create();
        $form->addSubmit('send', 'Change password');
        $form->onSuccess[] = $this->changePasswordFormFactory->saveFormSubmitted;
        $form->onSuccess[] = function (Form $form) {
            $this->flashSuccess("Password changed successfully.");
            $this->redirect('Edit:', array("id" => $this->user->id));
        };
//        $form->onError[] = function (Form $form) {
//            $this->flashDanger("An error occurred.");
//            foreach($form->errors as $err){
//                $this->flashDanger($err);
//            }
//        };
        return $form;
    }

    public function actionDefault()
    {
        if ($user = $this->userManager->find($this->user->id)) {
            $form = $this['changePasswordForm'];
        } else {
            throw new BadRequestException;
        }
    }
}
