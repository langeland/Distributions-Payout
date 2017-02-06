<?php
namespace Langeland\Payout\Domain\Model;

/*
 * This file is part of the Langeland.Payout package.
 */

use Neos\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * @Flow\Entity
 */
class Account
{

    /**
     * @var string
     */
    protected $card;

    /**
     * @var integer
     */
    protected $balance;

    /**
     * @return string
     */
    public function getCard()
    {
        return $this->card;
    }

    /**
     * @param string $card
     * @return Account
     */
    public function setCard($card)
    {
        $this->card = $card;
        return $this;
    }

    /**
     * @return int
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * @param int $balance
     * @return Account
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;
        return $this;
    }


}
