<?php

namespace FiWallet\App\FrontModule\FiltersModule;

use FiWallet\App\FrontModule\FiltersModule\Forms\FilterFormFactory;
use FiWallet\App\FrontModule\BasePresenter;
use Nette\Application\UI\Form;

/**
 * @author Adam Studenic
 * @author Pavel Kouřil <pavel.kouril@hotmail.com>
 */
class AddPresenter extends BasePresenter
{
    /**
     * @inject
     * @var FilterFormFactory
     */
    public $filterFormFactory;

    protected function createComponentFilterForm()
    {
        $form = $this->filterFormFactory->create();
        $form->addSubmit('send', 'Create new filter');
        $form->onSuccess[] = $this->filterFormFactory->addFormSubmitted;
        $form->onSuccess[] = function (Form $form) {
            $this->flashSuccess("New filter successfully created!");
            $this->redirect('List:');
        };
        return $form;
    }
}
