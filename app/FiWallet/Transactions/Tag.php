<?php

namespace FiWallet\Transactions;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FiWallet\Users\User;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Kdyby\Doctrine\Entities\BaseEntity;

/**
 * @ORM\Entity()
 * @ORM\Table(name="tags")
 *
 * @author Pavel Kouřil <pavel.kouril@hotmail.com>
 *
 * @property-read User $user
 * @property string $name
 * @property-read Transaction[]|ArrayCollection $transactions
 */
class Tag extends BaseEntity
{
    use Identifier;

    /**
     * @ORM\Column()
     * @var string
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="Transaction", mappedBy="tags")
     * @var ArrayCollection|Transaction[]
     */
    private $transactions;

    /**
     * @ORM\ManyToOne(targetEntity="FiWallet\Users\User")
     * @ORM\JoinColumn()
     * @var User
     */
    private $user;

    /**
     * @param User $user
     * @param string $name
     */
    public function __construct(User $user, $name)
    {
        parent::__construct();
        $this->user = $user;
        $this->name = $name;
        $this->transactions = new ArrayCollection();
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return ArrayCollection|Transaction[]
     */
    public function getTransactions()
    {
        return $this->transactions;
    }

    public function addTransaction(Transaction $transaction)
    {
        $transaction->addTag($this);
        $this->transactions->add($transaction);
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

    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name
        ];
    }
}
