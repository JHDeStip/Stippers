<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Class to do database operations regarding money transactions.
 */

require_once __DIR__.'/../../helperClasses/database/Database.php';
require_once 'MoneyTransaction.php';
require_once 'MoneyTransactionDBException.php';

abstract class MoneyTransactionDB {
    
    /**
     * Gets all money transactions.
     * 
     * @param int $limit the max amount of transactions to return
     * @return array money trancactions
     * @throws Exception generic error for if something goes wrong while talking to the database
     * @throws MoneyTransactionDBException error for if something goes wrong while getting the transactions
     */
    public static function getTransactions($limit) {
        try {
            $conn = Database::getConnection();
            $commString = 'SELECT transaction_id, affected_user, bal_before, incr_money, decr_money, discount_perc, from_prize, time, executing_browser_name, executing_user FROM stippers_money_transactions ORDER BY time DESC LIMIT ?';
            $stmt = $conn->prepare($commString);
            
            //Check if statement could be prepared
            if ($stmt) {
                
                $stmt->bind_param('i', $limit);
                if (!$stmt->execute())
                    throw new BrowserDBException('Unknown error during statement execution while getting transactions.', MoneyTransactionDBException::UNKNOWNERROR);
                else {
                    $stmt->bind_result($transactionId, $affectedUser, $balBefore, $incrMoney, $decrMoney, $discountPerc, $fromPrize, $time, $executingBrowserName, $executingUser);
                    $transactions = array();
                    
                    while ($stmt->fetch())
                        array_push($transactions, new MoneyTransaction($transactionId, $affectedUser, $balBefore, $incrMoney, $decrMoney, $discountPerc, $fromPrize, $time, $executingBrowserName, $executingUser));
                    
                    return $transactions;
                }
            }
            else
                throw new MoneyTransactionDBException('Cannot prepare statement.', MoneyTransactionDBException::CANNOTPREPARESTMT);
        }
        catch (Exception $ex) {
            throw $ex;
        }
        finally {
            if (isset($conn)) {
                $conn->kill($conn->thread_id);
                $conn->close();
            }
        }
    }
    
    /**
     * Gets all money transactions.
     * 
     * @param int $fromTimeStamp the unix timestamp from where to get transactions
     * @param int $toTimeStamp the unix timestamp to where to get transactions
     * @return array money trancactions
     * @throws Exception generic error for if something goes wrong while talking to the database
     * @throws MoneyTransactionDBException error for if something goes wrong while getting the transactions
     */
    public static function getTransactionsBetween($fromTimeStamp, $toTimeStamp) {
        try {
            $conn = Database::getConnection();
            $commString = 'SELECT transaction_id, affected_user, bal_before, incr_money, decr_money, discount_perc, from_prize, time, executing_browser_name, executing_user FROM stippers_money_transactions WHERE time >= FROM_UNIXTIME(?) AND time < FROM_UNIXTIME(?) ORDER BY time DESC';
            $stmt = $conn->prepare($commString);
            
            //Check if statement could be prepared
            if ($stmt) {
                
                $stmt->bind_param('ii', $fromTimeStamp, $toTimeStamp);
                if (!$stmt->execute())
                    throw new BrowserDBException('Unknown error during statement execution while getting transactions.', MoneyTransactionDBException::UNKNOWNERROR);
                else {
                    $stmt->bind_result($transactionId, $affectedUser, $balBefore, $incrMoney, $decrMoney, $discountPerc, $fromPrize, $time, $executingBrowserName, $executingUser);
                    $transactions = array();
                    
                    while ($stmt->fetch())
                        array_push($transactions, new MoneyTransaction($transactionId, $affectedUser, $balBefore, $incrMoney, $decrMoney, $discountPerc, $fromPrize, $time, $executingBrowserName, $executingUser));
                    
                    return $transactions;
                }
            }
            else
                throw new MoneyTransactionDBException('Cannot prepare statement.', MoneyTransactionDBException::CANNOTPREPARESTMT);
        }
        catch (Exception $ex) {
            throw $ex;
        }
        finally {
            if (isset($conn)) {
                $conn->kill($conn->thread_id);
                $conn->close();
            }
        }
    }

    /**
     * Gets money transactions for a user who's ID is given.
     * 
     * @param int $userId ID of use to bet transactions for
     * @param int $limit max amount of transactions to return
     * @return array money trancactions
     * @throws Exception generic error for if something goes wrong while talking to the database
     * @throws MoneyTransactionDBException error for if something goes wrong while getting the transactions
     */
    public static function getTransactionsByUserId($userId, $limit) {
        try {
            $conn = Database::getConnection();
            $commString = 'SELECT transaction_id, affected_user, bal_before, incr_money, decr_money, discount_perc, from_prize, time, executing_browser_name, executing_user '
                    .'FROM stippers_money_transactions '
                    .'WHERE affected_user = ? '
                    .'ORDER BY time DESC '
                    .'LIMIT ?';
            $stmt = $conn->prepare($commString);
            //Check if statement could be prepared
            if ($stmt) {
                
                $stmt->bind_param('ii', $userId, $limit);
                
                if (!$stmt->execute())
                    throw new BrowserDBException('Unknown error during statement execution while getting transactions.', MoneyTransactionDBException::UNKNOWNERROR);
                else {
                    $stmt->bind_result($transactionId, $affecteduser, $balBefore, $incrMoney, $decrMoney, $discountPerc, $fromPrize, $time, $executingBrowserName, $executingUser);
                    $transactions = array();
                    
                    while ($stmt->fetch())
                        array_push($transactions, new MoneyTransaction($transactionId, $affecteduser, $balBefore, $incrMoney, $decrMoney, $discountPerc, $fromPrize, $time, $executingBrowserName, $executingUser));
                    
                    return $transactions;
                }
            }
            else
                throw new MoneyTransactionDBException('Cannot prepare statement.', MoneyTransactionDBException::CANNOTPREPARESTMT);
        }
        catch (Exception $ex) {
            throw $ex;
        }
        finally {
            if (isset($conn)) {
                $conn->kill($conn->thread_id);
                $conn->close();
            }
        }
    }
    
    /**
     * Gest the total number of transactions for a user who's ID is given.
     * 
     * @param int $userId ID of user to get number of transactions for
     * @return int number of transactions
     * @throws Exception generic error for if something goes wrong while talking to the database
     * @throws MoneyTransactionDBException error for if something goes wrong while getting the number of transactions
     */
    public static function getTotalTransactionsByUserId($userId){
        try {
            $conn = Database::getConnection();
            $commString = 'SELECT count(*) FROM stippers_money_transactions WHERE affected_user = ?';
            $stmt = $conn->prepare($commString);
            //Check if statement could be prepared
            if ($stmt) {
                                
                $stmt->bind_param('i', $userId);
                if (!$stmt->execute()) 
                    throw new MoneyTransactionDBException('Unknown error during statement execution while counting the user\'s transactions.', MoneyTransactionDBException::UNKNOWNERROR);
                else {
                    $stmt->bind_result($nTransactions);
                    if ($stmt->fetch())
                        return $nTransactions;
                    else
                        throw new MoneyTransactionDBException('Unknown error during statement execution while counting the user\'s transactions.', MoneyTransactionDBException::UNKNOWNERROR);
                }
            }
            else
                throw new MoneyTransactionDBException('Cannot prepare statement.', MoneyTransactionDBException::CANNOTPREPARESTMT);
        }
        catch (Exception $ex) {
            throw $ex;
        }
        finally
        {
            if (isset($conn)){
                $conn->kill($conn->thread_id);
                $conn->close();
            }
        }
    }
    
    /**
     * Gets the total number of transactions.
     * 
     * @return int number of transactions
     * @throws Exception generic error for if something goes wrong while talking to the database
     * @throws MoneyTransactionDBException error for if something goes wrong while getting the number of transactions
     */
    public static function getTotalTransactions(){
        try {
            $conn = Database::getConnection();
            $commString = 'SELECT count(*) FROM stippers_money_transactions';
            $stmt = $conn->prepare($commString);
            
            //Check if statement could be prepared
            if ($stmt) {
                
                if (!$stmt->execute())
                    throw new MoneyTransactionDBException('Unknown error during statement execution while counting the transactions.', MoneyTransactionDBException::UNKNOWNERROR);
                else {
                    $stmt->bind_result($nTransactions);
                    if ($stmt->fetch())
                        return $nTransactions;
                    else
                        throw new MoneyTransactionDBException('Unknown error during statement execution while counting the transactions.', MoneyTransactionDBException::UNKNOWNERROR);
                }
            }
            else
                throw new MoneyTransactionDBException('Cannot prepare statement.', MoneyTransactionDBException::CANNOTPREPARESTMT);
        }
        catch (Exception $ex) {
            throw $ex;
        }
        finally
        {
            if (isset($conn)){
                $conn->kill($conn->thread_id);
                $conn->close();
            }
        }
    }
    
    /**
     * Adds a transaction and updates tha balance of the user account.
     * 
     * @user User to add the transaction for
     * @param Transaction $transaction transaction to add
     * @throws Exception generic error for if something goes wrong while talking to the database
     * @throws TransactionDBException error for if something goes wrong while adding the transaction
     */
    public static function addTransaction($user, $transaction) {
        try {
            $conn = Database::getConnection();
            
            $conn->autocommit(false);
            
            $commString = 'UPDATE stippers_users SET balance = ? '
                    .'WHERE user_id = ? AND email = ? AND first_name = ? AND last_name = ? AND password_hash = ? AND balance = ? AND phone = ? AND date_of_birth = STR_TO_DATE(?, "%d/%m/%Y") AND street = ? AND house_number = ? AND city = ? AND postal_code = ? AND country = ? AND DATE_FORMAT(CONVERT_TZ(creation_time, @@global.time_zone, ?), "%d/%m/%Y %H:%i") = ? AND is_admin = ? AND is_hint_manager = ? AND is_user_manager = ? AND is_browser_manager = ? AND is_money_manager = ?';
            $stmt = $conn->prepare($commString);
            
            //Check if statement could be prepared
            if ($stmt) {
                
                $timezone = GlobalConfig::MYSQL_TIME_ZONE;
                $balAfter = $transaction->getBalAfter();
                $stmt->bind_param('iissssisssssssssiiiii', $balAfter, $user->userId, $user->email, $user->firstName, $user->lastName, $user->passwordHash, $user->balance, $user->phone, $user->dateOfBirth, $user->street, $user->houseNumber, $user->city, $user->postalCode, $user->country, $timezone, $user->creationTime, $user->isAdmin, $user->isHintManager, $user->isUserManager, $user->isBrowserManager, $user->isMoneyManager);
                
                if (!$stmt->execute())
                    throw new MoneyTransactionDBException('Unknown error during statement execution while updating user.', MoneyTransactionDBException::UNKNOWNERROR);
                else if ($stmt->affected_rows == 0)
                    throw new MoneyTransactionDBException('The user is out of date, someone else has probably already changed the user.', MoneyTransactionDBException::USEROUTOFDATE);
                
                $stmt->close();
                
                $commString = 'INSERT INTO stippers_money_transactions (affected_user, bal_before, incr_money, decr_money, discount_perc, from_prize, executing_browser_name, executing_user) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';
                $stmt = $conn->prepare($commString);
                
                //Check if statement could be prepared
                if ($stmt) {
                    
                    $balBefore = $transaction->getBalBefore();
                    $incrMoney = $transaction->getIncrMoney();
                    $decrMoney = $transaction->getDecrMoney();
                    $discountPerc = $transaction->getDiscountPerc();
                    $fromPrize = $transaction->isFromPrize();
                    $executingBrowserName = $transaction->getExecutingBrowserName();
                    $executingUser = $transaction->getExecutingUser();
                    $stmt->bind_param('iiiiiisi', $user->userId, $balBefore, $incrMoney, $decrMoney, $discountPerc, $fromPrize, $executingBrowserName, $executingUser);
                    
                    if (!$stmt->execute())
                        throw new MoneyTransactionDBException('Unknown error during statement execution while adding the transaction.', MoneyTransactionDBException::UNKNOWNERROR);
                    else
                        $conn->commit();
                }
                else
                    throw new MoneyTransactionDBException('Cannot prepare statement.', MoneyTransactionDBException::CANNOTPREPARESTMT);
            }
            else
               throw new MoneyTransactionDBException('Cannot prepare statement.', MoneyTransactionDBException::CANNOTPREPARESTMT);
        }
        catch (Exception $ex) {
            if (isset($conn))
                $conn->rollback();
            throw $ex;
        }
        finally {
            if (isset($conn)) {
                $conn->kill($conn->thread_id);
                $conn->close();
            }
        }
    }
    
    /**
     * Gest the total amount of money that's currently in the system.
     * 
     * @return int total balance
     * @throws Exception generic error for if something goes wrong while talking to the database
     * @throws MoneyTransactionDBException error for if something goes wrong while getting the total balance
     */
    public static function getTotalBalance(){
        try {
            $conn = Database::getConnection();
            $commString = 'SELECT SUM(balance) FROM stippers_users';
            $stmt = $conn->prepare($commString);
            //Check if statement could be prepared
            if ($stmt) {

                if (!$stmt->execute()) 
                    throw new MoneyTransactionDBException('Unknown error during statement execution while getting the total balance.', MoneyTransactionDBException::UNKNOWNERROR);
                else {
                    $stmt->bind_result($totalBalance);
                    if ($stmt->fetch())
                        return $totalBalance;
                    else
                        throw new MoneyTransactionDBException('Unknown error during statement execution while getting the total balance.', MoneyTransactionDBException::UNKNOWNERROR);
                }
            }
            else
                throw new MoneyTransactionDBException('Cannot prepare statement.', MoneyTransactionDBException::CANNOTPREPARESTMT);
        }
        catch (Exception $ex) {
            throw $ex;
        }
        finally
        {
            if (isset($conn)){
                $conn->kill($conn->thread_id);
                $conn->close();
            }
        }
    }
}