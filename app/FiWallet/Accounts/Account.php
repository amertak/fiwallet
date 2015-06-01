<?php

namespace FiWallet\Accounts;

use Doctrine\ORM\Mapping as ORM;
use FiWallet\Users\User;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Kdyby\Doctrine\Entities\BaseEntity;

/**
 * @ORM\Entity()
 * @ORM\Table(name="accounts")
 *
 * @author Pavel Kouřil <pavel.kouril@hotmail.com>
 *
 * @property-read User $user
 * @property-read string $currency
 * @property string $name
 * @property float $balance
 */
class Account extends BaseEntity
{
    use Identifier;

    /**
     * @ORM\ManyToOne(targetEntity="FiWallet\Users\User")
     * @ORM\JoinColumn()
     * @var User
     */
    private $user;

    /**
     * @ORM\Column()
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="decimal", precision=8, scale=2)
     * @var float
     */
    private $balance;

    /**
     * @ORM\Column(length=3)
     * @var string
     */
    private $currency;

    /**
     * @param User $user
     * @param string $name
     * @param string $currency
     * @param float $balance
     */
    public function __construct(User $user, $name, $currency, $balance = 0.0)
    {
        parent::__construct();
        $this->user = $user;
        $this->name = $name;
        $this->currency = $currency;
        $this->balance = $balance;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return float
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * @param float $balance
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;
    }

    /**
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'user' => $this->user->id,
            'name' => $this->name,
            'balance' => $this->balance,
            'currency' => $this->currency,
        ];
    }
}
