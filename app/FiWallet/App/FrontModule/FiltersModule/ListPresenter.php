<?php

namespace FiWallet\App\FrontModule\FiltersModule;

use FiWallet\App\FrontModule\BasePresenter;
use FiWallet\Filters\FilterManager;
use FiWallet\Filters\CustomFilter;
use Kdyby\Doctrine\EntityRepository;

/**
 * @author Adam Studenic
 * @author Pavel Kouřil <pavel.kouril@hotmail.com>
 */
class ListPresenter extends BasePresenter
{
    /**
     * @var EntityRepository
     */
    private $filtersRepository;

    /**
     * @inject
     * @var FilterManager
     */
    public $filterManager;

    protected function startup()
    {
        parent::startup();
        $this->filtersRepository = $this->entityManager->getRepository(CustomFilter::class);
    }

    public function actionDefault()
    {
        $this->template->filters = $this->filtersRepository->findBy(['user' => $this->user->identity]);
    }
}
