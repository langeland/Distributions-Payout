<?php
namespace Langeland\Payout\Command;

/*
 * This file is part of the Langeland.Payout package.
 */

use Langeland\Payout\Domain\Model\Account;
use Langeland\Payout\Domain\Repository\AccountRepository;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Cli\CommandController;

/**
 * @Flow\Scope("singleton")
 */
class AccountCommandController extends CommandController
{

    /**
     * @var AccountRepository
     * @Flow\Inject
     */
    protected $accountRepository;

    /**
     * An example command
     *
     * The comment of this command method is also used for TYPO3 Flow's help screens. The first line should give a very short
     * summary about what the command does. Then, after an empty line, you should explain in more detail what the command
     * does. You might also give some usage example.
     *
     * It is important to document the parameters with param tags, because that information will also appear in the help
     * screen.
     *
     * @param Account $account
     * @param integer $amount
     * @return void
     */
    public function depositCommand(Account $account, $amount)
    {
        $this->outputLine('Deposit %s on %s.', array($amount, $account->getCard()));

        $account->setBalance($account->getBalance() + $amount);
        $this->outputLine('New balance is: %s.', array($account->getBalance()));

        $this->accountRepository->update($account);
    }

    /**
     *
     * @return void
     */
    public function listCommand()
    {
        $accounts = $this->accountRepository->findAll();

        foreach ($accounts as $account) {

            \Neos\Flow\var_dump($account);
            $this->outputLine(print_r(get_class_methods($account),1));
            get_class($account);die();
            $this->outputLine('%s.', array($account));
        }
    }

}
