<?php

namespace FiWallet\App\ApiModule;

use FiWallet\Filters\CustomFilter;
use Kdyby\Doctrine\EntityRepository;

/**
 * @author Pavel Kouřil <pavel.kouril@hotmail.com>
 */
class FilterPresenter extends BasePresenter
{
    /**
     * @var EntityRepository
     */
    private $customFilterRepository;

    protected function startup()
    {
        parent::startup();
        $this->customFilterRepository = $this->entityManager->getRepository(CustomFilter::class);
    }

    /**
     * @GET filters
     */
    public function actionContent()
    {
        $this->resource->data = array_map(
            function (CustomFilter $t) {
                return $t->toArray();
            },
            $this->customFilterRepository->findBy(['user' => $this->user->identity])
        );
        $this->sendData();
    }
}
