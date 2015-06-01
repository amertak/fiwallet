<?php

namespace FiWallet\Notifications;

use FiWallet\Transactions\Transaction;
use FiWallet\Users\User;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Doctrine\EntityRepository;
use Nette\Object;
use Nette\Security\User as NetteUser;

/**
 * @author Pavel Kouřil <pavel.kouril@hotmail.com>
 */
class NotificationsManager extends Object
{
    /**
     * @var EntityRepository
     */
    private $notificationRepository;

    /**
     * @var NetteUser
     */
    private $user;

    /**
     * @var EntityManager
     */
    private $entityManager;

    public function __construct(NetteUser $user, EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->notificationRepository = $entityManager->getRepository(Notification::class);
        $this->user = $user;
    }

    /**
     * @param User $user
     * @param string $message
     */
    public function addTextNotification(User $user, $message)
    {
        $this->addNotification(new TextNotification(new \DateTime(), $user, $message));
    }

    /**
     * @param Transaction $transaction
     * @param string $message
     */
    public function addTransactionNotification(Transaction $transaction, $message)
    {
        $this->addNotification(new TransactionNotification(new \DateTime(), $message, $transaction));
    }

    private function addNotification(Notification $notification)
    {
        $this->entityManager->persist($notification);
        $this->entityManager->flush($notification);
    }

    /**
     * @return Notification[]
     */
    public function getNotifications()
    {
        return $this->notificationRepository->findBy(['user' => $this->user->identity], ['dateTime' => 'desc']);
    }

    /**
     * @param int $id
     *
     * @return Notification|null
     */
    public function markAsRead($id)
    {
        /** @var Notification $notification */
        if ($notification = $this->notificationRepository->findOneBy(['id' => $id, 'user' => $this->user->identity])) {
            $notification->isRead = true;
            $this->entityManager->flush($notification);
            return $notification;
        }
        return null;
    }

    public function markAllAsRead()
    {
        /** @var Notification $notification */
        foreach ($this->getNotifications() as $notification) {
            $notification->isRead = true;
        }
        $this->entityManager->flush();
    }

    public function getUnreadNotificationCount()
    {
        return $this->notificationRepository
            ->createQueryBuilder('n')->select('count(n.id)')
            ->where('n.isRead = false and n.user = :user')
            ->setParameter('user', $this->user->identity)->getQuery()->getSingleScalarResult();
    }
}
