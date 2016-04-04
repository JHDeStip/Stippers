<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Model representing a money transaction.
 */

class MoneyTransaction {
    private $transactionId;
    private $affectedUser;
    private $balBefore;
    private $incrMoney;
    private $decrMoney;
    private $discountPerc;
    private $fromPrize;
    private $time;
    private $executingBrowserName;
    private $executingUser;
    
    /**
     * Constructor for money transaction
     * 
     * @param int $transactionId id of the transaction
     * @param int $affectedUser the id of the user the transaction applies to
     * @param int $balBefore the balance in cents before the transaction
     * @param int $incrMoney the amount of money in cents added
     * @param int $decrMoney the amount of money in cents taken off
     * @param int $discountPerc the discount percentage times 100
     * @param bool $fromPrize indicates if the incrMoney comes from a prize or bonus. If this is false the money comes from real money.
     * @param string $time the time of the transaction
     * @param string $executingBrowserName the name of the browser that did the transaction
     * @param string $executingUser the id of the user that did the transaction
     */
    public function __construct($transactionId, $affectedUser, $balBefore, $incrMoney, $decrMoney, $discountPerc, $fromPrize, $time, $executingBrowserName, $executingUser) {
        $this->transactionId = $transactionId;
        $this->affectedUser = $affectedUser;
        $this->balBefore = $balBefore;
        $this->incrMoney = $incrMoney;
        $this->decrMoney = $decrMoney;
        $this->discountPerc = $discountPerc;
        $this->fromPrize = $fromPrize;
        $this->time = $time;
        $this->executingBrowserName = $executingBrowserName;
        $this->executingUser = $executingUser;
    }
    
    /**
     * Gets the ID of the tranaction.
     * 
     * @return int ID of transaction
     */
    public function getTransactionId() {
        return $this->transactionId;
    }
    
    /**
     * Gets the ID of the user that this transaction applies to.
     * 
     * @return int ID of user
     */
    public function getAffectedUser() {
        return $this->affectedUser;
    }
    
    /**
     * Gets the balance in cents before the transaction.
     * 
     * @return int balance in cents before transaction
     */
    public function getBalBefore() {
        return $this->balBefore;
    }
    
    /**
     * Gets the money in cents that's added to the balance.
     * 
     * @return int money in cents added
     */
    public function getIncrMoney() {
        return $this->incrMoney;
    }
    
    /**
     * Gets the money in cents that's taken off the balance.
     * 
     * @return int money in cents taken off
     */
    public function getDecrMoney() {
        return $this->decrMoney;
    }
    
    /**
     * Gets the money in cents that's taken off the balance with the discount taken into account.
     * 
     * @return int money in cents taken off
     */
    public function getDecrMoneyWithDiscount() {
        return $this->decrMoney - ceil($this->decrMoney * $this->discountPerc / 100);
    }
    
    /**
     * Gets the discount percentage times 100.
     * 
     * @return int discount percentage times 100
     */
    public function getDiscountPerc() {
        return $this->discountPerc;
    }
    
    /**
     * Returns whether the incrMoney comes from real money or from a prize or bonus
     * 
     * @return bool discount percentage times 100
     */
    public function isFromPrize() {
        return $this->fromPrize;
    }
    
    /**
     * Gets the time of the transaction.
     * 
     * @return string time of transaction
     */
    public function getTime() {
        return $this->time;
    }
    
    /**
     * Gets the name of the browser that did the transaction.
     * 
     * @return string name of browser that did the transaction
     */
    public function getExecutingBrowserName() {
        return $this->executingBrowserName;
    }
    
    /**
     * Gets the ID of the user that did the transaction.
     * 
     * @return int ID of the user that did the transaction
     */
    public function getExecutingUser() {
        return $this->executingUser;
    }
    
    /**
     * Calculates the balance in cents after the transaction.
     * 
     * @return int balance in cents after transaction
     */
    public function getBalAfter() {
        return $this->balBefore + $this->incrMoney - ($this->decrMoney - ceil($this->decrMoney * $this->discountPerc / 100));  
    }
    
    /**
     * Calculates the discount in cents. This is always rounded up in favour of the customer.
     * 
     * @return int discount in cents
     */
    public function getDiscount() {
        return ceil($this->decrMoney * $this->discountPerc / 100);
    }
}