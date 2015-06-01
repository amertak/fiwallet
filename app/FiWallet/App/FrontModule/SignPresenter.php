<?php

namespace FiWallet\App\FrontModule;

use FiWallet\Users\RegistrationProcess;
use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;

/**
 * @author Pavel Kouřil <pavel.kouril@hotmail.com>
 */
class SignPresenter extends BasePresenter
{
    /**
     * @inject
     * @var RegistrationProcess
     */
    public $registrationProcess;

    protected function startup()
    {
        parent::startup();
        if ($this->user->loggedIn) {
            $this->redirect('Dashboard:');
        }
    }

    protected function createComponentRegistrationForm()
    {
        $form = new Form();
        $form->addText('username', 'Username')->setRequired('Please fill in your username.');
        $form->addText('email', 'E-mail')->setRequired('Please fill in your email.');
        $form->addText('fullName', 'Your full name')->setRequired('Please fill in your name.');
        $form->addPassword('password', 'Password')->setRequired('Please fill in your password.');
        $form->addPassword('password2', 'Password (again)')->setRequired()->addRule($form::EQUAL, 'Passwords must match.', $form['password']);
        $form->addSubmit('send', 'Sign up');
        $form->onSuccess[] = function (Form $form) {
            if ($user = $this->registrationProcess->register($form->values->email, $form->values->username, $form->values->fullName, $form->values->password)) {
                $this->user->login($user);
                $this->flashSuccess('Successfully registered and logged in.');
                $this->redirect('Dashboard:');
            } else {
                $form->addError("Error while registering user.");
                $this->flashDanger('Error while registering user.');
            }
        };
        return $form;
    }

    protected function createComponentLoginForm()
    {
        $form = new Form();
        $form->addText('username', 'Username')->setRequired('Please fill in your username.');
        $form->addPassword('password', 'Password')->setRequired('Please fill in your password.');
        $form->addSubmit('send', 'Log in');
        $form->onSuccess[] = function (Form $form) {
            $values = $form->getValues();
            $this->getUser()->setExpiration('14 days', false);
            try {
                $this->getUser()->login($values->username, $values->password);
                $this->flashSuccess('Successfully logged in.');
                $this->redirect('Dashboard:');
            } catch (AuthenticationException $e) {
                $form->addError($e->getMessage());
                $this->flashDanger('Error while logging in user.');
            }
        };
        return $form;
    }
}
