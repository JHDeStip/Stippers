<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Controller for the my transactions page.
 */

require_once __DIR__.'/../IController.php';
require_once __DIR__.'/../../helperClasses/Page.php';

require_once __DIR__.'/../../config/MoneyTransactionsViewConfig.php';

require_once __DIR__.'/../../models/user/User.php';

require_once __DIR__.'/../../models/moneyTransaction/MoneyTransaction.php';
require_once __DIR__.'/../../models/moneyTransaction/MoneyTransactionDB.php';
require_once __DIR__.'/../../models/moneyTransaction/MoneyTransactionDBException.php';

abstract class MyTransactionsController implements IController {
    
    public static function get() {
        $page = new Page();
        $page->data['title'] = 'Transacties';
        
        //Gets the amount from GET or use default
        if (isset($_GET['amount']))
            $amount = $_GET['amount'];
        else
            $amount = MoneyTransactionsViewConfig::DEFAULTAMOUNT;
        
        try {
            //Get user for his name
            $page->data['TransactionsNameView']['fullName'] = $_SESSION['Stippers']['user']->getFullName();
            
            //Get transactions for user
            $transactions = MoneyTransactionDB::getTransactionsByUserId($_SESSION['Stippers']['user']->userId, $amount);
            $transactionCount = count($transactions);
            
            //If no transactions show no transactions view, otherwise show list with transactions
            if ($transactionCount > 0) {
                $page->data['TransactionsNoDiscountListView']['transactions'] = $transactions;
                $page->data['TransactionsNoDiscountListView']['totalAmount'] = $transactionCount;
                $page->addView('transactions/TransactionsTitleView');
                $page->addView('transactions/TransactionsNameView');
                $page->addView('transactions/TransactionsBackToProfileLinkView');
                $page->addView('transactions/TransactionsNoDiscountListView');
            }
            else {
                $page->data['InfoMessageNoDescriptionWithLinkView']['infoTitle'] = 'Er zijn nog geen transacties';
                $page->data['InfoMessageNoDescriptionWithLinkView']['redirectUrl'] = 'profile';
                $page->addView('transactions/TransactionsTitleView');
                $page->addView('transactions/TransactionsNameView');
                $page->addView('info/InfoMessageNoDescriptionWithLinkView');
            }
            
            
        }
        catch (Exception $ex) {
            $page->data['ErrorMessageNoDescriptionWithLinkView']['errorTitle'] = 'Kan gegevens niet ophalen uit de database';
            $page->data['ErrorMessageNoDescriptionWithLinkView']['tryAgainUrl'] = $_SERVER['REQUEST_URI'];
            $page->addView('error/ErrorMessageNoDescriptionWithLinkView');
        }
        
        $page->showWithMenu();
    } 
    
    public static function post() {
        ChangePasswordController::get();
    }
}