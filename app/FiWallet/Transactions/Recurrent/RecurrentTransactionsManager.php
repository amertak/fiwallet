<?php

namespace FiWallet\Transactions\Recurrent;

use FiWallet\Transactions\TransactionsManager;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Doctrine\EntityRepository;
use Nette\Object;

/**
 * @author Pavel Kouřil <pavel.kouril@hotmail.com>
 */
class RecurrentTransactionsManager extends Object
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var EntityRepository
     */
    private $recurrentTransactionRepository;

    /**
     * @var TransactionsManager
     */
    private $transactionsManager;

    public function __construct(EntityManager $entityManager, TransactionsManager $transactionsManager)
    {
        $this->entityManager = $entityManager;
        $this->recurrentTransactionRepository = $entityManager->getRepository(RecurrentTransaction::class);
        $this->transactionsManager = $transactionsManager;
    }

    /**
     * @param int $id
     *
     * @return RecurrentTransaction|null
     */
    public function find($id)
    {
        return $this->recurrentTransactionRepository->find($id);
    }

    public function create($type, $fromTransaction, $name, $amount, \DateTime $firstOccurence, $occurenceInterval, $description = null)
    {
        if ($transaction = $this->transactionsManager->find($fromTransaction)) {
            $recTran = new RecurrentTransaction($type, $transaction->account, $name, $amount, $occurenceInterval, $firstOccurence);
            $recTran->description = $description ?: null;
            $this->entityManager->persist($recTran);
            $this->entityManager->flush($recTran);
            return $recTran;
        }
        // @todo better exception :|
        throw new \Exception();
    }

    public function processAll()
    {
        /** @var RecurrentTransaction[] $recTransactions */
        $recTransactions = $this->recurrentTransactionRepository->findBy(['isActive' => true]);
        foreach ($recTransactions as $recTransaction) {
            if ($this->checkIfRecurrentTransactionOccursToday($recTransaction)) {
                $this->createTransactionFromRecurrentTransaction($recTransaction);
            }
        }
    }

    private function checkIfRecurrentTransactionOccursToday(RecurrentTransaction $recTransaction)
    {
        $date = clone $recTransaction->latestOccurrence;
        if ($recTransaction->type == RecurrentTransaction::TYPE_DAILY) {
            $date->modify("+$recTransaction->occurenceInterval days");
        }
        if ($recTransaction->type == RecurrentTransaction::TYPE_WEEKLY) {
            $date->modify("+$recTransaction->occurenceInterval weeks");
        }
        if ($recTransaction->type == RecurrentTransaction::TYPE_MONTHLY) {
            $date->modify("+$recTransaction->occurenceInterval months");
        }
        return $date->format('Y-m-d') == (new \DateTime())->format('Y-m-d');
    }

    private function createTransactionFromRecurrentTransaction(RecurrentTransaction $recurrentTransaction)
    {
        $today = (new \DateTime())->setTime(0, 0);
        $recurrentTransaction->latestOccurrence = $today;
        $this->entityManager->flush($recurrentTransaction);
        return $this->transactionsManager->create(
            $recurrentTransaction->account,
            $recurrentTransaction->name,
            $recurrentTransaction->amount,
            $today,
            $recurrentTransaction->tags,
            null,
            $recurrentTransaction
        );
    }

    public function update(RecurrentTransaction $trans)
    {
        $this->entityManager->flush($trans);
    }
}
