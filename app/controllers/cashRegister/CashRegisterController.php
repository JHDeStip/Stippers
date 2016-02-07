<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Controller for the cash register page.
 */

require_once __DIR__.'/../IController.php';
require_once __DIR__.'/../../helperClasses/Page.php';

require_once __DIR__.'/../../helperClasses/safeMath/SafeMath.php';

require_once __DIR__.'/../../config/MoneyTransactionConfig.php';

require_once __DIR__.'/../../models/user/User.php';
require_once __DIR__.'/../../models/user/UserDB.php';
require_once __DIR__.'/../../models/user/UserDBException.php';

require_once __DIR__.'/../../models/browser/Browser.php';
require_once __DIR__.'/../../models/browser/BrowserDB.php';
require_once __DIR__.'/../../models/browser/BrowserDBException.php';

require_once __DIR__.'/../../models/moneyTransaction/MoneyTransaction.php';
require_once __DIR__.'/../../models/moneyTransaction/MoneyTransactionDB.php';
require_once __DIR__.'/../../models/moneyTransaction/MoneyTransactionDBException.php';

require_once __DIR__.'/../../views/cashRegister/CashRegisterEnterCardViewValidator.php';
require_once __DIR__.'/../../views/cashRegister/CashRegisterEnterTransactionViewValidator.php';

abstract class CashRegisterController implements IController {
    
    public static function get() {
        $page = new Page();
        $page->data['title'] = 'Kassa';
        CashRegisterController::buildEnterCardView($page, false);
        $page->showWithMenu();
    }
    
    public static function post() {
        $page = new Page();
        $page->data['title'] = 'Kassa';
        
        if (isset($_POST['to_enter_transaction_view'])) {
            $errMsgs = CashRegisterEnterCardViewValidator::validate($_POST);
            
            if (empty($errMsgs)) {
                try {
                    $_SESSION['Stippers']['CashRegister']['user'] = UserDB::getFullUserByCardNumber($_POST['card_number']);
                    CashRegisterController::buildEnterTransactionView($page, false);
                }
                catch (UserDBException $ex) {
                    if ($ex->getCode() == UserDBException::NOUSERFORCARDNUMER) {
                        CashRegisterController::buildEnterCardView($page, true);
                        $page->data['CashRegisterEnterCardView']['errMsgs']['global'] = '<h2 class="error_message" id="enter_card_form_error_message">Dit kaartnummer is niet gekoppeld aan een gebruiker.</h2>';
                    }
                    else {
                        CashRegisterController::buildEnterCardView($page, true);
                        $page->data['CashRegisterEnterCardView']['errMsgs']['global'] = '<h2 class="error_message" id="enter_card_form_error_message">Kan gebruiker niet ophalen, probeer opnieuw.</h2>';
                    }
                }
                catch (Exception $ex) {
                    CashRegisterController::buildEnterCardView($page, true);
                        $page->data['CashRegisterEnterCardView']['errMsgs']['global'] = '<h2 class="error_message" id="enter_card_form_error_message">Kan gebruiker niet ophalen, probeer opnieuw.</h2>';
                }
            }
            else {
                CashRegisterController::buildEnterCardView($page, true);
                $page->data['CashRegisterEnterCardView']['errMsgs'] = array_merge($page->data['CashRegisterEnterCardView']['errMsgs'], $errMsgs);
            }
        }
        
        elseif (isset($_POST['register_transaction'])) {
            $errMsgs = CashRegisterEnterTransactionViewValidator::validate($_POST);
                    
            if (empty($errMsgs)) {
                try {
                    $decrMoney = ($_POST['decrease_money'] == '' ? 0 : SafeMath::getCentsFromString($_POST['decrease_money']));
                    $executingBrowserName = BrowserDB::getBrowserById($_SESSION['Stippers']['browser']->browserId)->name;
                    
                    $trans = new MoneyTransaction(null, $_SESSION['Stippers']['CashRegister']['user']->userId, $_SESSION['Stippers']['CashRegister']['user']->balance, 0, $decrMoney, MoneyTransactionConfig::DEFAULTDISCOUNTPERC, null, $executingBrowserName, null);
                    
                    if ($trans->getBalAfter() < 0) {
                        $page->data['ErrorMessageWithDescriptionWithLinkView']['tryAgainUrl'] = $_SERVER['REQUEST_URI'];
                        $page->data['ErrorMessageWithDescriptionWithLinkView']['errorTitle'] = 'Saldo te laag';
                        $page->data['ErrorMessageWithDescriptionWithLinkView']['errorDescription'] = 'Het saldo de kaart is te laag.<br/>Je komt onder nul uit.';
                        $page->addView('error/ErrorMessageWithDescriptionWithLinkView');
                    }
                    else {
                        MoneyTransactionDB::addTransaction($_SESSION['Stippers']['CashRegister']['user'], $trans);
                        $page->data['CashRegisterTransactionResultView']['balBefore'] = $trans->getBalBefore() / 100;
                        $page->data['CashRegisterTransactionResultView']['balAfter'] = $trans->getBalAfter() / 100;
                        $page->data['CashRegisterTransactionResultView']['discount'] = $trans->getDiscount() / 100;
                        $page->data['CashRegisterTransactionResultView']['decrMoney'] = $trans->getDecrMoney() / 100;
                        $page->addView('cashRegister/CashRegisterTransactionResultView');
                    }
                }
                catch (Exception $ex) {
                    CashRegisterController::buildEnterTransactionView($page, true);
                    $page->data['CashRegisterEnterTransactionView']['errMsgs']['global'] = '<h2 class="error_message" id="enter_transaction_form_error_message">Kan transactie niet registreren, probeer opnieuw.</h2>';
                }
            }
            else {
                CashRegisterController::buildEnterTransactionView($page, true);
                $page->data['CashRegisterEnterTransactionView']['errMsgs'] = array_merge($page->data['CashRegisterEnterTransactionView']['errMsgs'], $errMsgs);
            }
        }
        
        $page->showWithMenu();
    }
    

    /**
     * Builds the view to enter the transaction
     * 
     * @param Page $page page to add the view to
     */
    private static function buildEnterTransactionView(Page $page, $enterMode) {
        $page->data['CashRegisterEnterTransactionView']['errMsgs'] = CashRegisterEnterTransactionViewValidator::initErrMsgs();
        $page->data['CashRegisterEnterTransactionView']['enter_transaction_formAction'] = $_SERVER['REQUEST_URI'];
        $page->data['CashRegisterEnterTransactionView']['fullName'] = $_SESSION['Stippers']['CashRegister']['user']->getFullName();
        $page->data['CashRegisterEnterTransactionView']['currentBalance'] = $_SESSION['Stippers']['CashRegister']['user']->balance;
        if ($enterMode)
            $page->data['CashRegisterEnterTransactionView']['decreaseMoney'] = $_POST['decrease_money'];
        else
            $page->data['CashRegisterEnterTransactionView']['decreaseMoney'] = '';
        
        $page->addView('cashRegister/CashRegisterEnterTransactionView');
    }
    
    
    /**
     * Builds the view to enter the user's card number
     * 
     * @param Page $page page to add the view to
     */
    private static function buildEnterCardView(Page $page, $enterMode) {
        $page->data['CashRegisterEnterCardView']['errMsgs'] = CashRegisterEnterCardViewValidator::initErrMsgs();
        $page->data['CashRegisterEnterCardView']['enter_card_formAction'] = $_SERVER['REQUEST_URI'];
        
        if ($enterMode)
            $page->data['CashRegisterEnterCardView']['cardNumber'] = $_POST['card_number'];
        else
            $page->data['CashRegisterEnterCardView']['cardNumber'] = '';
        
        $page->addView('cashRegister/CashRegisterEnterCardView');
        
        $page->addExtraJsFile('barcodeScanner/BarcodeScanner.js');
        $page->addExtraJsFile('views/cashRegister/CashRegisterBarcodeScanner.js');
        $page->addExtraJsFile('views/cashRegister/cashRegisterEnterCardFormOnLoadHandler.js');
    }
}