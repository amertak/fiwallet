<?php

namespace FiWallet\Users;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FiWallet\Accounts\Account;
use Kdyby\Doctrine\Entities\Attributes\Identifier;
use Kdyby\Doctrine\Entities\BaseEntity;
use Nette\Security\IIdentity;

/**
 * @ORM\Entity()
 * @ORM\Table(name="users")
 *
 * @author Pavel Kouřil <pavel.kouril@hotmail.com>
 *
 * @property-read string $username
 * @property string $password
 * @property string $email
 * @property string $fullName
 * @property-read ArrayCollection|Account[] $accounts
 */
class User extends BaseEntity implements IIdentity
{
    use Identifier;

    /**
     * @ORM\Column(unique=true)
     * @var string
     */
    private $email;

    /**
     * @ORM\Column()
     * @var string
     */
    private $fullName;

    /**
     * @ORM\Column()
     * @var string
     */
    private $username;

    /**
     * @ORM\Column()
     * @var string
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity="FiWallet\Accounts\Account", mappedBy="user")
     * @var ArrayCollection|Account[]
     */
    private $accounts;

    /**
     * @param string $username
     * @param string $password
     * @param string $email
     * @param string $fullName
     */
    public function __construct($username, $password, $email, $fullName)
    {
        parent::__construct();
        $this->accounts = new ArrayCollection();
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
        $this->fullName = $fullName;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getRoles()
    {
        return [];
    }

    /**
     * @return ArrayCollection|Account[]
     */
    public function getAccounts()
    {
        return $this->accounts;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * @param string $fullName
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;
    }
}
