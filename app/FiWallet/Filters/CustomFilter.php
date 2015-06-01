<?php

namespace FiWallet\Filters;

use Doctrine\ORM\Mapping as ORM;
use FiWallet\Users\User;

/**
 * @ORM\Entity()
 *
 * @author Pavel Kouřil <pavel.kouril@hotmail.com>
 *
 * @property-read User $user
 */
class CustomFilter extends Filter
{
    /**
     * @ORM\ManyToOne(targetEntity="FiWallet\Users\User")
     * @ORM\JoinColumn()
     * @var User
     */
    protected $user;

    /**
     * @param User $user
     * @param string $name
     * @param array[] $conditionData
     *
     * @throws \Exception
     */
    public function __construct(User $user, $name, $conditionData)
    {
        parent::__construct($name, $conditionData);
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}
