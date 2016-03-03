<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Controller for the all transactions page.
 */

require_once __DIR__.'/../IController.php';
require_once __DIR__.'/../../helperClasses/Page.php';

require_once __DIR__.'/../../config/MoneyTransactionsViewConfig.php';

require_once __DIR__.'/../../models/moneyTransaction/MoneyTransaction.php';
require_once __DIR__.'/../../models/moneyTransaction/MoneyTransactionDB.php';
require_once __DIR__.'/../../models/moneyTransaction/MoneyTransactionDBException.php';

abstract class AllTransactionsController implements IController {
    
    public static function get() {
        $page = new Page();
        $page->data['title'] = 'Transacties';
        
        //Gets the amount from GET or use default
        if (isset($_GET['amount']))
            $amount = $_GET['amount'];
        else
            $amount = MoneyTransactionsViewConfig::DEFAULT_AMOUNT;
        
        try {
            //Get all transactions
            $transactions = MoneyTransactionDB::getTransactions($amount);
            $transactionCount = count($transactions);
            
            //If no transactions show no transactions view, otherwise show list with transactions
            if ($transactionCount > 0) {
                $page->data['TransactionsWithDetailsWithDiscountListView']['transactions'] = $transactions;
                $page->addView('transactions/TransactionsTitleView');
                $page->addView('transactions/TransactionsBackToManageUserLinkView');
                $page->addView('transactions/TransactionsWithDetailsWithDiscountListView');
            }
            else {
                $page->addView('transactions/TransactionsTitleView');
                $page->data['InfoMessageNoDescriptionWithLinkView']['infoTitle'] = 'Er zijn nog geen transacties';
                $page->data['InfoMessageNoDescriptionWithLinkView']['redirectUrl'] = 'manageuser';
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