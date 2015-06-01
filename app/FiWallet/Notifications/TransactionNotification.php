<?php

namespace FiWallet\Notifications;

use Doctrine\ORM\Mapping as ORM;
use FiWallet\Transactions\Transaction;
use FiWallet\Users\User;

/**
 * @ORM\Entity()
 *
 * @author Pavel Kouřil <pavel.kouril@hotmail.com>
 *
 * @property-read Transaction $transaction
 */
class TransactionNotification extends Notification
{
    /**
     * @ORM\ManyToOne(targetEntity="FiWallet\Transactions\Transaction")
     * @ORM\JoinColumn()
     * @var Transaction
     */
    private $transaction;

    /**
     * @param \DateTime $dateTime
     * @param string $message
     * @param Transaction $transaction
     */
    public function __construct(\DateTime $dateTime, $message, Transaction $transaction)
    {
        parent::__construct($dateTime, $transaction->user, $message);
        $this->transaction = $transaction;
    }

    /**
     * @return Transaction
     */
    public function getTransaction()
    {
        return $this->transaction;
    }
}
