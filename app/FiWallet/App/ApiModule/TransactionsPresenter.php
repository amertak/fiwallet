<?php

namespace FiWallet\App\ApiModule;

use Doctrine\DBAL\DBALException;
use FiWallet\Accounts\Account;
use FiWallet\Transactions\Transaction;
use FiWallet\Transactions\TransactionsManager;
use Kdyby\Doctrine\EntityRepository;

/**
 * @author Pavel Kouřil <pavel.kouril@hotmail.com>
 */
class TransactionsPresenter extends BasePresenter
{
    /**
     * @inject
     * @var TransactionsManager
     */
    public $transactionsManager;

    /**
     * @var EntityRepository
     */
    private $transactionsRepository;

    /**
     * @var EntityRepository
     */
    private $accountsRepository;

    protected function startup()
    {
        parent::startup();
        $this->transactionsRepository = $this->entityManager->getRepository(Transaction::class);
        $this->accountsRepository = $this->entityManager->getRepository(Account::class);
    }

    /**
     * @GET transactions
     *
     * @param int|null $limit
     */
    public function actionContent($limit = null)
    {
        $this->resource->data = array_map(function (Transaction $t) {
            return $t->toArray();
        }, $this->transactionsRepository->findBy(['user' => $this->user->identity], ['dateOfTransaction' => 'desc'], $limit));
        $this->sendData();
    }

    /**
     * @POST transactions
     */
    public function actionCreate()
    {
        $data = $this->getInput()->getData();
        /** @var Account $account */
        $account = $this->accountsRepository->find($data['accountId']);
        $transaction = $this->transactionsManager->create($account, $data['name'], $data['amount'], \DateTime::createFromFormat('Y-m-d', $data['date']), []);
        $this->resource->data = $transaction->toArray();
        $this->sendData();
    }

    /**
     * @GET transactions/<id>
     */
    public function actionDetail($id)
    {
        /** @var Transaction $transaction */
        if ($transaction = $this->transactionsRepository->find($id)) {
            if ($transaction->user->id != $this->user->identity->id) {
                $this->sendError(401, 'This data is unavailable for currently logged in user.');
            }
            $this->resource->data = $transaction->toArray();
            $this->sendData();
        } else {
            $this->sendError(404, "Transaction $id does not exist.");
        }
    }

    /**
     * @PUT transactions/<id>
     */
    public function actionUpdate($id)
    {
        /** @var Transaction $transaction */
        if ($transaction = $this->transactionsRepository->find($id)) {
            if ($transaction->user->id != $this->user->identity->id) {
                $this->sendError(401, 'This data is unavailable for currently logged in user.');
            }
            $data = $this->getInput()->getData();
            $tags = isset($data['tags']) ? $this->transactionsManager->convertStringsToTags($this->user->identity, $data['tags']) : [];
            $this->resource->data = $this->transactionsManager->update($transaction, $data['name'], $data['amount'], $tags, \DateTime::createFromFormat('Y-m-d', $data['date']), $data['notes'])->toArray();
            $this->sendData();
        } else {
            $this->sendError(404, "Transaction $id does not exist.");
        }
    }


    /**
     * @PUT transactions/<id>/confirm
     */
    public function actionConfirm($id)
    {
        /** @var Transaction $transaction */
        if ($transaction = $this->transactionsRepository->find($id)) {
            if ($transaction->user->id != $this->user->identity->id) {
                $this->sendError(401, 'This data is unavailable for currently logged in user.');
            }
            $this->transactionsManager->confirm($transaction);
            $this->resource->data = $transaction->toArray();
            $this->sendData();
        } else {
            $this->sendError(404, "Transaction $id does not exist.");
        }
    }

    /**
     * @DELETE transactions/<id>
     */
    public function actionDelete($id)
    {
        /** @var Transaction $transaction */
        if ($transaction = $this->transactionsRepository->find($id)) {
            if ($transaction->user->id != $this->user->identity->id) {
                $this->sendError(401, 'This data is unavailable for currently logged in user.');
            }
            try {
                $transaction = $this->transactionsManager->delete($transaction);
                $this->resource->data = $transaction->toArray();
                $this->sendData();
            } catch (DBALException $e) {
                $this->sendError(500, "Can't delete. :(");
            }
        } else {
            $this->sendError(404, "Transaction $id does not exist.");
        }
    }
}
