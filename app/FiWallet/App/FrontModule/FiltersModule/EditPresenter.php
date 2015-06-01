<?php

namespace FiWallet\App\FrontModule\FiltersModule;

use FiWallet\Filters\Condition;
use FiWallet\Filters\FilterManager;
use FiWallet\App\FrontModule\FiltersModule\Forms\FilterFormFactory;
use FiWallet\App\FrontModule\BasePresenter;
use Nette\Application\BadRequestException;
use Nette\Application\UI\Form;

/**
 * @author Adam Studenic
 * @author Pavel Kouřil <pavel.kouril@hotmail.com>
 */
class EditPresenter extends BasePresenter
{
    /**
     * @inject
     * @var FilterFormFactory
     */
    public $filterFormFactory;

    /**
     * @inject
     * @var FilterManager
     */
    public $filterManager;

    protected function createComponentFilterForm()
    {
        $form = $this->filterFormFactory->create();
        $form->addSubmit('send', 'Edit filter');
        $form->onSuccess[] = $this->filterFormFactory->editFormSubmitted;
        $form->onSuccess[] = function (Form $form) {
            $this->flashSuccess("Filter successfully edited.");
            $this->redirect('this');
        };
        return $form;
    }

    public function actionDefault($id)
    {
        if ($filter = $this->filterManager->find($id)) {
            /** @var Form $form */
            $form = $this['filterForm'];
            $form['id']->setDefaultValue($id);
            $form['name']->setDefaultValue($filter->name);

            $old = $form->addContainer('old');
            foreach ($filter->conditions as $cond) {
                $cont = $old->addContainer($cond->id);
                $cont->addSelect('property', 'Choose transaction attribute', ['amount' => 'Transaction amount', 'tags' => 'Transaction tag'])->setDefaultValue($cond->property);
                $cont->addSelect(
                    'operator',
                    'Operator',
                    [
                        Condition::OPERATOR_EQ => '=',
                        Condition::OPERATOR_GT => '>',
                        Condition::OPERATOR_GTE => '>=',
                        Condition::OPERATOR_LT => '<',
                        Condition::OPERATOR_LTE => '<='
                    ]
                )->setDefaultValue($cond->operator);
                $cont->addText('value', 'Value')->setDefaultValue($cond->value);
            }
            $this->template->conditions = $filter->conditions;
        } else {
            throw new BadRequestException;
        }
    }

    public function handleDeleteCondition($id, $conditionId)
    {
        if ($this->filterManager->deleteCondition($conditionId)) {
            $this->flashInfo('Condition successfully deleted.');
        } else {
            $this->flashDanger("Can't delete condition. Each filter has to have at least 1 condition.");
        }

        $this->redirect('this');
    }
}
