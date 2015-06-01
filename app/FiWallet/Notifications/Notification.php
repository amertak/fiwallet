<?php

namespace FiWallet\Notifications;

use Doctrine\ORM\Mapping as ORM;
use FiWallet\Users\User;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Kdyby\Doctrine\Entities\BaseEntity;

/**
 * @ORM\Entity()
 * @ORM\Table(name="notifications")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"text"="TextNotification", "transaction"="TransactionNotification"})
 *
 * @author Pavel Kouřil <pavel.kouril@hotmail.com>
 *
 * @property-read User $user
 * @property-read \DateTime $dateTime
 * @property-read string $data
 * @property bool $isRead
 */
abstract class Notification extends BaseEntity
{
    use Identifier;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $dateTime;

    /**
     * @ORM\ManyToOne(targetEntity="FiWallet\Users\User")
     * @ORM\JoinColumn()
     * @var User
     */
    private $user;

    /**
     * @ORM\Column(type="text")
     * @var string
     */
    private $data;

    /**
     * @ORM\Column(type="boolean")
     * @var bool
     */
    private $isRead = false;

    /**
     * @param \DateTime $dateTime
     * @param User $user
     * @param string $message
     */
    public function __construct(\DateTime $dateTime, User $user, $message)
    {
        parent::__construct();
        $this->dateTime = $dateTime;
        $this->user = $user;
        $this->data = $message;
    }

    /**
     * @return bool
     */
    public function isIsRead()
    {
        return $this->isRead;
    }

    /**
     * @param bool $isRead
     */
    public function setIsRead($isRead)
    {
        $this->isRead = $isRead;
    }

    /**
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return \DateTime
     */
    public function getDateTime()
    {
        return $this->dateTime;
    }
}
