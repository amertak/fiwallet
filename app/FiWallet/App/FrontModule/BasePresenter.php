<?php

namespace FiWallet\App\FrontModule;

use FiWallet\Notifications\NotificationsManager;
use FiWallet\App\FrontModule\Components\NotificationsControl;
use Kdyby\Doctrine\EntityManager;
use Nette\Application\UI\Presenter;

/**
 * @author Pavel Kouřil <pavel.kouril@hotmail.com>
 */
abstract class BasePresenter extends Presenter
{
    /**
     * @inject
     * @var EntityManager
     */
    public $entityManager;

    /**
     * @inject
     * @var NotificationsManager
     */
    public $notificationsManager;

    protected function startup()
    {
        parent::startup();
        if (!$this->user->loggedIn && $this->name != 'Front:Sign') {
            $this->redirect('Sign:');
        }
    }

    protected function createComponentNotifications()
    {
        return new NotificationsControl($this->notificationsManager);
    }

    public function flashSuccess($message)
    {
        $this->flashMessage($message, 'alert-success');
    }

    public function flashWarning($message)
    {
        $this->flashMessage($message, 'alert-warning');
    }

    public function flashDanger($message)
    {
        $this->flashMessage($message, 'alert-danger');
    }

    public function flashInfo($message)
    {
        $this->flashMessage($message, 'alert-info');
    }

    public function handleLogout()
    {
        $this->getUser()->logout();
        $this->redirect(":Front:Sign:");
    }

    protected function beforeRender()
    {
        parent::beforeRender();
        $bundleJs = $this->context->getParameters()['wwwDir'] . '/js/bundle.js';
        $this->template->bundleJsVersion = file_exists($bundleJs) ? md5(file_get_contents($this->context->getParameters()['wwwDir'] . '/js/bundle.js')) : "404";
    }
}
