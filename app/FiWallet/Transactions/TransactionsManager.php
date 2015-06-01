<?php

namespace FiWallet\Transactions;

use FiWallet\Accounts\Account;
use FiWallet\Transactions\Recurrent\RecurrentTransaction;
use FiWallet\Users\User;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Doctrine\EntityRepository;
use Nette\Object;
use Nette\Utils\Strings;

/**
 * @author Pavel Kouřil <pavel.kouril@hotmail.com>
 */
class TransactionsManager extends Object
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
     * Stores new Transaction
     *
     * @param Account $account
     * @param string $name
     * @param float $amount
     * @param \DateTime $dateOfTransaction
     * @param Tag[] $tags
     * @param string|null $notes
     * @param RecurrentTransaction|null $recurrentTransaction
     *
     * @return Transaction
     */
    public function create(Account $account, $name, $amount, \DateTime $dateOfTransaction, $tags, $notes = null, RecurrentTransaction $recurrentTransaction = null)
    {
        $transaction = new Transaction($account->user, $account, $name, $amount, $dateOfTransaction, $recurrentTransaction);
        $transaction->notes = $notes;
        if ($transaction->isConfirmed) {
            $account->balance += $amount;
        }
        $this->entityManager->persist($transaction);
        $this->entityManager->flush();
        return $transaction;
    }

    /**
     * @param Transaction $transaction
     * @param string $name
     * @param float $amount
     * @param Tag[] $tags
     * @param \DateTime $dateOfTransaction
     * @param string|null $notes
     *
     * @return Transaction
     */
    public function update(Transaction $transaction, $name, $amount, $tags, \DateTime $dateOfTransaction, $notes = null)
    {
        if ($transaction->isConfirmed) {
            $transaction->account->balance -= $transaction->amount - $amount;
        }
        $transaction->amount = $amount;
        $transaction->name = $name;
        $dateOfTransaction->setTime(0, 0, 0);
        $transaction->dateOfTransaction = $dateOfTransaction;
        $transaction->notes = $notes ?: null;
        $transaction->tags->clear();
        foreach ($tags as $tag) {
            $transaction->addTag($tag);
        }
        $this->entityManager->flush();
        return $transaction;
    }

    public function delete(Transaction $transaction)
    {
        if ($transaction->isConfirmed) {
            $transaction->account->balance -= $transaction->amount;
        }
        $this->entityManager->remove($transaction);
        $this->entityManager->flush();
        return $transaction;
    }

    /**
     * @param User $user
     * @param string[] $tags
     *
     * @return Tag[]
     */
    public function convertStringsToTags(User $user, array $tags)
    {
        $result = [];
        $toSave = [];
        foreach ($tags as $tag) {
            $fixedTagName = Strings::lower($tag);
            $tagExists = (bool)$this->tagRepository->createQueryBuilder('t')
                ->select('count(t.id)')->where('t.name = :name and t.user = :user')
                ->setParameters(['name' => $fixedTagName, 'user' => $user])
                ->getQuery()->getSingleScalarResult();
            if ($tagExists) {
                $result[] = $this->tagRepository->createQueryBuilder('t')
                    ->where('t.name = :name and t.user = :user')
                    ->setParameters(['name' => $fixedTagName, 'user' => $user])
                    ->getQuery()->getSingleResult();
            } else {
                $toSave[] = $obj = new Tag($user, $fixedTagName);
                $result[] = $obj;
                $this->entityManager->persist($obj);
            }
        }
        $this->entityManager->flush($toSave);
        return $result;
    }

    /**
     * @param int $id
     *
     * @return Transaction|null
     */
    public function find($id)
    {
        return $this->transactionRepository->find($id);
    }

    /**
     * @param Transaction $transaction
     * @return Transaction
     */
    public function confirm(Transaction $transaction)
    {
        if (!$transaction->isConfirmed) {
            $transaction->isConfirmed = true;
            $transaction->account->balance += $transaction->amount;
            $this->entityManager->flush();
        }
        return $transaction;
    }

    public function getTransactionFromTo(User $user,$from, $to) {

        return $this->transactionRepository->createQueryBuilder('t')
            ->where('t.dateOfTransaction > :from AND t.dateOfTransaction < :to AND t.user = :user')
            ->setParameters(['from' => $from, 'to' => $to, 'user' => $user])
            ->getQuery()
            ->getResult();
    }

    public function getTransactionFilter(User $user,$from=null, $to=null, $fromAmount=null, $toAmount=null) {

         $query = $this->transactionRepository->createQueryBuilder('t')
            ->where('t.user = :user')
            ->setParameters(['user' => $user]);


        if($from){
            $query->andWhere("t.dateOfTransaction > ?1")->setParameter(1, $from);
        }

        if($to){
            $query->andWhere("t.dateOfTransaction < ?2")->setParameter(2, $to);
        }
        if($fromAmount){
            $query->andWhere("t.amount > ?3")->setParameter(3, $fromAmount);
        }
        if($toAmount){
            $query->andWhere("t.amount < ?4")->setParameter(4, $toAmount);
        }

        return    $query->getQuery()->getResult();
    }
    /**
     * @param int $id
     *
     * @return Transaction|null
     */
    public function findAll()
    {
        return $this->transactionRepository->findAll();
    }
    /**
     * @param int $id
     *
     * @return Transaction|null
     */
    public function findBy(User $user)
    {
        return $this->transactionRepository->createQueryBuilder('t')
            ->where('t.user = :user')
            ->setParameters(['user' => $user])
            ->getQuery()
            ->getResult();
    }

}
