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
    private $user;
    private $balBefore;
    private $incrMoney;
    private $decrMoney;
    private $discountPerc;
    private $time;
    private $browserName;
    
    /**
     * Constructor for money transaction
     * 
     * @param int $transactionId id of the transaction
     * @param int $user user the transaction applies to
     * @param int $balBefore the balance in cents before the transaction
     * @param int $incrMoney the amount of money in cents added
     * @param int $decrMoney the amount of money in cents taken off
     * @param int $discountPerc the discount percentage times 100
     * @param string $time the time of the transaction
     * @param string $browserName the name of the browser that did the transaction
     */
    public function __construct($transactionId, $user, $balBefore, $incrMoney, $decrMoney, $discountPerc, $time, $browserName) {
        $this->tranactionId = $transactionId;
        $this->user = $user;
        $this->balBefore = $balBefore;
        $this->incrMoney = $incrMoney;
        $this->decrMoney = $decrMoney;
        $this->discountPerc = $discountPerc;
        $this->time = $time;
        $this->browserName = $browserName;
    }
    
    /**
     * Gets the user ID associated with the transaction.
     * 
     * @return int ID of user
     */
    public function getUser() {
        return $this->user;
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
     * Gets the discount percentage times 100.
     * 
     * @return int discount percentage times 100
     */
    public function getDiscountPerc() {
        return $this->discountPerc;
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
     * Gets the name of the browser that d the transaction.
     * 
     * @return string name of browser that did the transaction
     */
    public function getBrowserName() {
        return $this->browserName;
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