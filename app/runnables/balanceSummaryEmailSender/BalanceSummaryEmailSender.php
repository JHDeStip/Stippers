<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Runnable to send a summary of the balance on user accounts to the receivers set in config.
 */

require_once __DIR__.'/../IRunnable.php';

require_once __DIR__.'/../../config/GlobalConfig.php';
require_once __DIR__.'/../../config/BalanceSummaryEmailSenderConfig.php';

require_once __DIR__.'/../../helperClasses/email/Email.php';
require_once __DIR__.'/../../helperClasses/email/EmailException.php';

require_once __DIR__.'/../../models/user/User.php';
require_once __DIR__.'/../../models/user/UserDB.php';
require_once __DIR__.'/../../models/user/UserDBException.php';

require_once __DIR__.'/../../models/moneyTransaction/MoneyTransaction.php';
require_once __DIR__.'/../../models/moneyTransaction/MoneyTransactionDB.php';
require_once __DIR__.'/../../models/moneyTransaction/MoneyTransactionDBException.php';

abstract class BalanceSummaryEmailSender implements IRunnable {
    
    public static function run() {
        try {
            //Create timestamps of start and end of week
            $fromTimeStamp = strtotime('monday last week '.GlobalConfig::PHP_TIME_ZONE);
            $toTimeStamp = strtotime('monday this week '.GlobalConfig::PHP_TIME_ZONE);
            
            //Get all gransactions for this week
            $transactions = MoneyTransactionDB::getTransactionsBetween($fromTimeStamp, $toTimeStamp);
            
            //Get total amount of money in the system right now
            $totalBalanceAfter = MoneyTransactionDB::getTotalBalance();
            
            //Get total amount of money added and removed during this week.
            $incrMoneyFromRealMoney = 0;
            $incrMoneyFromPrize = 0;
            $decrMoneyWithoutDiscount = 0;
            $decrMoneyWithDiscount = 0;
            foreach ($transactions as $transaction) {
                if ($transaction->isFromPrize())
                    $incrMoneyFromPrize += $transaction->getIncrMoney();
                else
                    $incrMoneyFromRealMoney += $transaction->getIncrMoney();
                
                $decrMoneyWithoutDiscount += $transaction->getDecrMoney();
                $decrMoneyWithDiscount += $transaction->getDecrMoneyWithDiscount();
            }
            
            //Get total amount of money in the system before this week
            $totalBalanceBefore = $totalBalanceAfter - $incrMoneyFromRealMoney - $incrMoneyFromPrize + $decrMoneyWithDiscount;
            
            //Create from and to time strings
            $fromTime = new DateTime();
            $fromTime->setTimeZone(new DateTimeZone(GlobalConfig::PHP_TIME_ZONE));
            $fromTime->setTimeStamp($fromTimeStamp);
            $fromTime->setTimeZone(new DateTimeZone(GlobalConfig::PHP_TIME_ZONE));
            $fromDateString = $fromTime->format('d/m/y');
            $fromTimeString = $fromTime->format('d/m/y H:i:s');
            
            $toTime = new DateTime();
            $toTime->setTimeZone(new DateTimeZone(GlobalConfig::PHP_TIME_ZONE));
            $toTime->setTimeStamp($toTimeStamp);
            //Subtract 1 second to display the correct date.
            $toTime = date_sub($toTime, new DateInterval('PT1S'));
            $toDateString = $toTime->format('d/m/y');
            $toTimeString = $toTime->format('d/m/y H:i:s');
            
            //Create array with date to show in the email
            $emailExtras['common']['fromDate'] = $fromDateString;
            $emailExtras['common']['toDate'] = $toDateString;
            $emailExtras['common']['fromTime'] = $fromTimeString;
            $emailExtras['common']['toTime'] = $toTimeString;
            $emailExtras['common']['totalBalanceBefore'] = $totalBalanceBefore/100;
            $emailExtras['common']['incrMoneyFromRealMoney'] = $incrMoneyFromRealMoney/100;
            $emailExtras['common']['incrMoneyFromPrize'] = $incrMoneyFromPrize/100;
            $emailExtras['common']['decrMoneyWithoutDiscount'] = $decrMoneyWithoutDiscount/100;
            $emailExtras['common']['decrMoneyWithDiscount'] = $decrMoneyWithDiscount/100;
            $emailExtras['common']['totalBalanceAfter'] = $totalBalanceAfter/100;
            
            //Create fake users to send email to addresses
            $users = array();
            foreach (BalandeSummaryEmailSenderConfig::EMAIL_ADDRESSES as $emailAddress) {
                $user = new User();
                $user->email = $emailAddress;
                array_push($users, $user);
            }
            
            //Send email
            Email::sendEmails('MoneyOverview.html', 'Saldo lidkaarten '.$fromDateString.' - '.$toDateString, 'info@stip.be', $users, $emailExtras);
        }
        catch (Exception $ex) {
            var_dump($ex);
        }
    }
}