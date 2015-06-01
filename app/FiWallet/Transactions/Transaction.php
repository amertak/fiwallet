<?php

namespace FiWallet\Transactions;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FiWallet\Accounts\Account;
use FiWallet\Transactions\Recurrent\RecurrentTransaction;
use FiWallet\Users\User;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Kdyby\Doctrine\Entities\BaseEntity;

/**
 * @ORM\Entity()
 * @ORM\Table(name="transactions")
 *
 * @author Pavel Kouřil <pavel.kouril@hotmail.com>
 *
 * @property-read User $user
 * @property-read \DateTime $created
 * @property Account $account
 * @property string $name
 * @property string|null $notes
 * @property float $amount
 * @property \DateTime $dateOfTransaction
 * @property-read Tag[]|ArrayCollection $tags
 * @property bool $isConfirmed
 * @property RecurrentTransaction $recurrentTransaction
 */
class Transaction extends BaseEntity
{
    use Identifier;

    /**
     * @ORM\ManyToOne(targetEntity="FiWallet\Accounts\Account")
     * @ORM\JoinColumn()
     * @var Account
     */
    private $account;

    /**
     * @ORM\Column()
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @var string|null
     */
    private $notes;

    /**
     * @ORM\Column(type="decimal", precision=8, scale=2)
     * @var float
     */
    private $amount;

    /**
     * @ORM\Column(type="date")
     * @var \DateTime
     */
    private $dateOfTransaction;

    /**
     * @ORM\ManyToMany(targetEntity="Tag", inversedBy="tags")
     * @ORM\JoinTable(name="transactions_tags")
     * @var ArrayCollection|Tag[]
     */
    private $tags;

    /**
     * @ORM\ManyToOne(targetEntity="FiWallet\Users\User")
     * @ORM\JoinColumn()
     * @var User
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $created;

    /**
     * @ORM\Column(type="boolean")
     * @var bool
     */
    private $isConfirmed = true;

    /**
     * @ORM\ManyToOne(targetEntity="FiWallet\Transactions\Recurrent\RecurrentTransaction")
     * @ORM\JoinColumn()
     * @var RecurrentTransaction
     */
    private $recurrentTransaction;

    /**
     * @param User $user
     * @param Account $account
     * @param string $name
     * @param float $amount
     * @param \DateTime $dateOfTransaction
     * @param RecurrentTransaction|null $recurrentTransaction
     */
    public function __construct(User $user, Account $account, $name, $amount, \DateTime $dateOfTransaction, RecurrentTransaction $recurrentTransaction = null)
    {
        parent::__construct();
        $this->user = $user;
        $this->created = new \DateTime();
        $this->account = $account;
        $this->name = $name;
        $this->amount = $amount;
        $this->dateOfTransaction = $dateOfTransaction;
        $this->tags = new ArrayCollection();
        $this->recurrentTransaction = $recurrentTransaction;
        if ($recurrentTransaction instanceof RecurrentTransaction) {
            $this->isConfirmed = false;
        }
    }

    /**
     * @return Account
     */
    public function getAccount()
    {
        return $this->account;
    }

    public function setAccount(Account $account)
    {
        $this->account = $account;
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
     * @return null|string
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * @param null|string $notes
     */
    public function setNotes($notes = null)
    {
        $this->notes = $notes ? $notes : null;
    }

    /**
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     */
    public function setAmount($amount)
    {
        $this->amount = (float)$amount;
    }

    /**
     * @return \DateTime
     */
    public function getDateOfTransaction()
    {
        return $this->dateOfTransaction;
    }

    public function setDateOfTransaction(\DateTime $dateOfTransaction)
    {
        $this->dateOfTransaction = $dateOfTransaction;
    }

    /**
     * @return ArrayCollection|Tag[]
     */
    public function getTags()
    {
        return $this->tags;
    }

    public function addTag(Tag $tag)
    {
        $this->tags->add($tag);
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
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @return bool
     */
    public function getIsConfirmed()
    {
        return $this->isConfirmed;
    }

    /**
     * @param bool $isConfirmed
     */
    public function setIsConfirmed($isConfirmed)
    {
        $this->isConfirmed = $isConfirmed;
    }

    /**
     * @return RecurrentTransaction
     */
    public function getRecurrentTransaction()
    {
        return $this->recurrentTransaction;
    }

    public function setRecurrentTransaction(RecurrentTransaction $recurrentTransaction = null)
    {
        $this->recurrentTransaction = $recurrentTransaction;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'userId' => $this->user->id,
            'created' => $this->created,
            'accountId' => $this->account->id,
            'account' => $this->account->toArray(),
            'name' => $this->name,
            'notes' => $this->notes,
            'amount' => $this->amount,
            'date' => $this->dateOfTransaction,
            'tags' => array_map(function (Tag $tag) { return $tag->toArray(); }, $this->tags->toArray()),
            'recurrentTransactionId' => $this->recurrentTransaction ? $this->recurrentTransaction->id : null,
            'recurrentTransaction' => $this->recurrentTransaction ? $this->recurrentTransaction->toArray() : null,
            'confirmed' => $this->isConfirmed
        ];
    }
}
