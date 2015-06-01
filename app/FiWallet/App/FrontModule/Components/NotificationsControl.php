<?php

namespace FiWallet\App\FrontModule\Components;

use FiWallet\Notifications\NotificationsManager;
use FiWallet\Notifications\TransactionNotification;
use Nette\Application\UI\Control;
use Nette\Application\UI\ITemplate;

/**
 * @author Pavel Kouřil <pavel.kouril@hotmail.com>
 */
class NotificationsControl extends Control
{
    /**
     * @var NotificationsManager
     */
    private $notificationsManager;

    public function __construct(NotificationsManager $notificationsManager)
    {
        parent::__construct();
        $this->notificationsManager = $notificationsManager;
    }

    public function render()
    {
        /** @var ITemplate $template */
        $template = $this->createTemplate();
        $template->setFile(__DIR__ . '/NotificationsControl.latte');
        $template->notifications = $this->notificationsManager->getNotifications();
        $template->unreadNotificationCount = $this->notificationsManager->getUnreadNotificationCount();
        $template->render();
    }

    public function handleNotificationClick($notificationId)
    {
        $notification = $this->notificationsManager->markAsRead($notificationId);
        if ($notification instanceof TransactionNotification) {
            $this->presenter->redirectUrl("/#/transaction/{$notification->transaction->id}");
        }
        $this->redirect('this');
    }

    public function handleReadAll()
    {
        $this->notificationsManager->markAllAsRead();
        $this->redirect('this');
    }
}
