<?php

namespace FiWallet\App\FrontModule\FiltersModule\Forms;

use FiWallet\Filters\Condition;
use FiWallet\Filters\FilterManager;
use FiWallet\App\FrontModule\Components\IFormFactory;
use Nette\Application\UI\Form;
use Nette\Object;
use Nette\Security\User as NetteUser;

/**
 * @author Adam Studenic
 * @author Pavel Kouřil <pavel.kouril@hotmail.com>
 *
 * @property-read callable $editFormSubmitted (Form) : void
 */
class FilterFormFactory extends Object implements IFormFactory
{
    /**
     * @var FilterManager
     */
    private $filterManager;

    /**
     * @var NetteUser
     */
    private $user;

    public function __construct(NetteUser $user, FilterManager $FilterManager)
    {
        $this->filterManager = $FilterManager;
        $this->user = $user;
    }

    public function create()
    {
        $form = new Form();
        $form->addHidden('id');
        $form->addText('name', 'Filter name')->setRequired('Please fill in name of filter.');
        $new = $form->addContainer('new');
        $new->addSelect('property', 'Choose transaction attribute', ['amount' => 'Transaction amount', 'tags' => 'Transaction tag']);
        $new->addSelect(
            'operator',
            'Operator',
            [
                Condition::OPERATOR_EQ => '=',
                Condition::OPERATOR_GT => '>',
                Condition::OPERATOR_GTE => '>=',
                Condition::OPERATOR_LT => '<',
                Condition::OPERATOR_LTE => '<='
            ]
        );
        $new->addText('value', 'Value');
        return $form;
    }

    /**
     * @param Form $form
     */
    public function addFormSubmitted(Form $form)
    {
        $values = $form->values;
        if ($values->new->value === "") {
            $form->addError('You have to fill in value for new filter.');
            return;
        }
        $this->filterManager->createFilter($this->user->identity, $values->name, [$values->new]);
    }

    /**
     * @param Form $form
     */
    public function editFormSubmitted(Form $form)
    {
        $values = $form->values;
        if ($Filter = $this->filterManager->find($values->id)) {
            $this->filterManager->editFilter($Filter, $values->name, $values->old, $values->new->value !== "" ? $values->new : null);
        } else {
            $form->addError("Filter you are trying to edit doesn't exist.");
        }
    }
}
