<?php

namespace FiWallet\App\FrontModule\Components;

use Nette\Application\UI\Form;

/**
 * @author Pavel Kouřil <pavel.kouril@hotmail.com>
 */
interface IFormFactory
{
    /**
     * @return Form
     */
    public function create();
}
