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
     * @var AccountRepository
     * @Flow\Inject
     */
    protected $accountRepository;

    /**
     * @return void
     */
    public function withdrawAction($card, $amount = null)
    {
        /** @var Account $account */
        $account = $this->accountRepository->findByCard($card);

        if (is_null($account)) {
            $return = array(
                'message' => 'Card not found, adding card',
                'status' => 404
            );

            $newAccount = new Account();
            $newAccount
                ->setCard($card)
                ->setBalance(0);

            $this->accountRepository->add($newAccount);

        } else {
            $return = array(
                'message' => 'Here is your money',
                'status' => 200,
                'amount' => $account->getBalance()
            );
            $account->setBalance(0);

            $this->accountRepository->update($account);
        }


        $this->view->assign('return', $return);
    }

}
