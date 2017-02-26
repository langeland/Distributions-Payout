<?php
namespace Langeland\Payout\Controller;

/*
 * This file is part of the Langeland.Payout package.
 */

use Langeland\Payout\Domain\Model\Account;
use Langeland\Payout\Domain\Repository\AccountRepository;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\Controller\ActionController;

class ApiController extends ActionController
{
    /**
     * @var string
     */
    protected $viewFormatToObjectNameMap = array(
        'json' => 'Neos\Flow\Mvc\View\JsonView'
    );

    /**
     * @var AccountRepository
     * @Flow\Inject
     */
    protected $accountRepository;

    /**
     * @var integer
     * @Flow\InjectConfiguration(path="withdraw.amount")
     */
    protected $withdrawAmount;

    /**
     * @param string $card
     * @param int|null $amount
     * @return void
     */
    public function withdrawAction($card, $amount = null)
    {
        /** @var Account $account */
        $account = $this->accountRepository->findOneByCard($card);
        if (is_null($account)) {
            $return = array(
                'message' => 'Card not found, adding card',
                'status' => 404,
                'balance' => 0,
                'amount' => 0
            );

            $newAccount = new Account();
            $newAccount
                ->setCard($card)
                ->setBalance(0);
            $this->accountRepository->add($newAccount);
        } else {
            if ($account->getBalance() - $this->withdrawAmount >= 0) {
                $return = array(
                    'message' => sprintf('Here is your money, you got %s remaining.', $account->getBalance() - $this->withdrawAmount),
                    'status' => 200,
                    'balance' => $account->getBalance() - $this->withdrawAmount,
                    'amount' => $this->withdrawAmount
                );
                $account->setBalance($account->getBalance() - $this->withdrawAmount);
            } else {
                $return = array(
                    'message' => sprintf('Nope !! You spent it all.'),
                    'status' => 200,
                    'balance' => 0,
                    'amount' => 0
                );
            }
            $this->accountRepository->update($account);
        }
        $this->view->assign('value', $return);
    }
}
