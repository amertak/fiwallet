<?php

namespace FiWallet\App\ApiModule;
use Kdyby\Doctrine\EntityRepository;
use FiWallet\Accounts\Account;

/**
 * @author Pavel Kouřil <pavel.kouril@hotmail.com>
 */
class AccountsPresenter extends BasePresenter
{
    /**
     * @var EntityRepository
     */
    private $accountsRepository;

    protected function startup()
    {
        parent::startup();
        $this->accountsRepository = $this->entityManager->getRepository(Account::class);
    }

    /**
     * @GET accounts
     */
    public function actionContent()
    {
        $this->resource->data = array_map(function (Account $t) {
            return $t->toArray();
        }, $this->accountsRepository->findBy(['user' => $this->user->identity]));
        $this->sendData();
    }

    /**
     * @GET accounts/<id>
     */
    public function actionDetail($id)
    {
        /** @var Account $account */
        if ($account = $this->accountsRepository->find($id)) {
            if ($account->user->id != $this->user->identity->id) {
                $this->sendError(401, 'This data is unavailable for currently logged in user.');
            }
            $this->resource->data = $account->toArray();
            $this->sendData();
        } else {
            $this->sendError(404, "Transaction $id does not exist.");
        }
    }
}
