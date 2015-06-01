<?php

namespace FiWallet\App\FrontModule\RecurrentTransactionsModule\Forms;

use FiWallet\App\FrontModule\Components\IFormFactory;
use FiWallet\Transactions\Recurrent\RecurrentTransaction;
use FiWallet\Transactions\Recurrent\RecurrentTransactionsManager;
use Nette\Application\UI\Form;
use Nette\Object;
use Nette\Security\User as NetteUser;

/**
 * @author Pavel Kouřil <pavel.kouril@hotmail.com>
 *
 * @property-read callable $addFormSubmitted (Form) : void
 * @property-read callable $editFormSubmitted (Form) : void
 */
class RecurrentTransactionFormFactory extends Object implements IFormFactory
{
    /**
     * @var RecurrentTransactionsManager
     */
    private $recurrentTransactionManager;

    /**
     * @var NetteUser
     */
    private $user;

    public function __construct(NetteUser $user, RecurrentTransactionsManager $recurrentTransactionManager)
    {
        $this->recurrentTransactionManager = $recurrentTransactionManager;
        $this->user = $user;
    }

    public function create()
    {
        $form = new Form();
        $form->addText('name', 'Name')->setRequired('Name is required.');
        $form->addText('amount', 'Amount')->setRequired()->addRule($form::FLOAT, 'Amount has to be a number');
        $form->addTextArea('description', 'Description/notes');
        $form->addSelect('type', ' ', ['daily' => 'days', 'weekly' => 'weeks', 'monthly' => 'months', 'yearly' => 'years'])->setRequired('Select repetition interval, please.');
        $form->addText('first', 'Start counting from')->setAttribute('type', 'date')->setRequired('Please fill in date.');
        $form->addText('interval', 'Repeat this transaction every')->setRequired('Please fill in interval for repetition.');
        return $form;
    }

    /**
     * @param Form $form
     */
    public function addFormSubmitted(Form $form)
    {
        $values = $form->values;
        $date = \DateTime::createFromFormat('Y-m-d', $values->first);
        if (!$date) {
            $form->addError("Date doesn't exist or format is wrong.");
            return;
        }
        if ($values->type == RecurrentTransaction::TYPE_DAILY || $values->type == RecurrentTransaction::TYPE_WEEKLY || $values->type == RecurrentTransaction::TYPE_MONTHLY) {
            $this->recurrentTransactionManager->create($values->type, $values->fromTransaction, $values->name, $values->amount, $date, $values->interval, $values->description);
        }
    }

    /**
     * @param Form $form
     */
    public function editFormSubmitted(Form $form)
    {
        $values = $form->values;
        $latestOccurrence = \DateTime::createFromFormat('Y-m-d', $values->first);
        if (!$latestOccurrence) {
            $form->addError("Date doesn't exist or format is wrong.");
            return;
        }
        if ($trans = $this->recurrentTransactionManager->find($values->id)) {
            $trans->name = $values->name;
            $trans->type = $values->type;
            $trans->isActive = $values->active;
            $trans->occurenceInterval = $values->interval;
            $trans->latestOccurrence = $latestOccurrence;
            $trans->description = $values->description ?: null;
            $trans->amount = $values->amount;
            $this->recurrentTransactionManager->update($trans);
        } else {
            $form->addError('Cannot edit recurrent transaction.');
            return;
        }
    }
}
