<?php

namespace FiWallet\Console\Commands;

use FiWallet\Transactions\Recurrent\RecurrentTransactionsManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Pavel Kouřil <pavel.kouril@hotmail.com>
 */
class ProcessRecurrentTransactions extends Command
{
    protected function configure()
    {
        $this->setName('fiwallet:process-recurrent-transactions')->setDescription('Checks all active recurrent transactions and creates standard transactions from them.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var RecurrentTransactionsManager $recTransManager */
        $recTransManager = $this->getHelper('container')->getByType(RecurrentTransactionsManager::class);
        $output->writeln("Checking recurrent transactions.");
        $recTransManager->processAll();
        $output->writeln("Done.");
    }
}
