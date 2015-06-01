<?php

namespace FiWallet\Transactions\Recurrent;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FiWallet\Accounts\Account;
use FiWallet\Transactions\Tag;
use FiWallet\Users\User;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Kdyby\Doctrine\Entities\BaseEntity;

/**
 * @ORM\Entity()
 * @ORM\Table(name="reccurent_transactions")
 *
 * @author Pavel Kouřil <pavel.kouril@hotmail.com>
 *
 * @property Account $account
 * @property-read User $user
 * @property string $name
 * @property string|null $description
 * @property float $amount
 * @property bool $isActive
 * @property int $occurenceInterval
 * @property \DateTime $firstOccurence
 * @property-read Tag[]|ArrayCollection $tags
 * @property \DateTime $latestOccurrence
 * @property string $type
 */
class RecurrentTransaction extends BaseEntity
{
    const TYPE_DAILY = "daily";
    const TYPE_WEEKLY = "weekly";
    const TYPE_MONTHLY = "monthly";
    const TYPE_YEARLY = "yearly";

    use Identifier;

    /**
     * @ORM\ManyToOne(targetEntity="FiWallet\Accounts\Account")
     * @ORM\JoinColumn()
     * @var Account
     */
    private $account;

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
     * @ORM\Column(type="text", nullable=true)
     * @var string|null
     */
    private $description;

    /**
     * @ORM\Column(type="decimal", precision=8, scale=2)
     * @var float
     */
    private $amount;

    /**
     * @ORM\Column(type="boolean")
     * @var bool
     */
    private $isActive = true;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    private $occurenceInterval = 1;

    /**
     * @ORM\Column(type="date")
     * @var \DateTime
     */
    private $firstOccurence;

    /**
     * @ORM\ManyToMany(targetEntity="FiWallet\Transactions\Tag", inversedBy="tags")
     * @ORM\JoinTable(name="recurrent_transactions_tags")
     * @var ArrayCollection|Tag[]
     */
    private $tags;

    /**
     * @ORM\Column(type="date")
     * @var \DateTime
     */
    private $latestOccurrence;

    /**
     * @ORM\Column()
     * @var string
     */
    private $type;

    /**
     * @param string $type
     * @param Account $account
     * @param string $name
     * @param float $amount
     * @param int $occurenceInterval
     * @param \DateTime $firstOccurence
     */
    public function __construct($type, Account $account, $name, $amount, $occurenceInterval, \DateTime $firstOccurence)
    {
        parent::__construct();
        $this->account = $account;
        $this->user = $account->user;
        $this->name = $name;
        $this->amount = $amount;
        $this->occurenceInterval = $occurenceInterval;
        $this->firstOccurence = $firstOccurence;
        $this->tags = new ArrayCollection();
        $this->latestOccurrence = clone $firstOccurence;
        $this->type = $type;
    }

    /**
     * @return \DateTime
     */
    public function getFirstOccurence()
    {
        return $this->firstOccurence;
    }

    public function setFirstOccurence(\DateTime $firstOccurence)
    {
        $this->firstOccurence = $firstOccurence;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
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
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
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
        $this->amount = $amount;
    }

    /**
     * @return bool
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * @param bool $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }

    /**
     * @return int
     */
    public function getOccurenceInterval()
    {
        return $this->occurenceInterval;
    }

    /**
     * @param int $occurenceInterval
     */
    public function setOccurenceInterval($occurenceInterval)
    {
        $this->occurenceInterval = $occurenceInterval;
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
     * @return \DateTime
     */
    public function getLatestOccurrence()
    {
        return $this->latestOccurrence;
    }

    public function setLatestOccurrence(\DateTime $latestOccurrence)
    {
        $this->latestOccurrence = $latestOccurrence;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'amount' => $this->name,
            'active' => $this->isActive,
        ];
    }
}
