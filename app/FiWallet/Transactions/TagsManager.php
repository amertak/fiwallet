<?php
/**
 * Created by PhpStorm.
 * User: arashidak
 * Date: 29/04/15
 * Time: 11:17
 */
namespace FiWallet\Transactions;

use FiWallet\Accounts\Account;
use FiWallet\Transactions\Recurrent\RecurrentTransaction;
use FiWallet\Users\User;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Doctrine\EntityRepository;
use Nette\Object;
use Nette\Utils\Strings;

class TagsManager extends Object
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var EntityRepository
     */
    private $tagRepository;

    /**
     * @var EntityRepository
     */
    private $transactionRepository;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->tagRepository = $entityManager->getRepository(Tag::class);
        $this->transactionRepository = $entityManager->getRepository(Transaction::class);
    }


    /**
     * @param int $id
     *
     * @return Tag|null
     */
    public function find($id)
    {
        return $this->tagRepository->find($id);
    }

    /**
     * @param int $id
     *
     * @return Tag|null
     */
    public function findAll()
    {
        return $this->tagRepository->findAll();
    }


}