<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Controller for the manage user money page.
 */

require_once __DIR__.'/../IController.php';
require_once __DIR__.'/../../helperClasses/Page.php';

require_once __DIR__.'/../../helperClasses/safeMath/SafeMath.php';

require_once __DIR__.'/../../config/MoneyTransactionConfig.php';

require_once __DIR__.'/../../models/user/User.php';
require_once __DIR__.'/../../models/user/UserDB.php';
require_once __DIR__.'/../../models/user/UserDBException.php';

require_once __DIR__.'/../../models/moneyTransaction/MoneyTransaction.php';
require_once __DIR__.'/../../models/moneyTransaction/MoneyTransactionDB.php';
require_once __DIR__.'/../../models/moneyTransaction/MoneyTransactionDBException.php';

require_once __DIR__.'/../../views/manageUserMoney/ManageUserMoneyEnterTransactionViewValidator.php';

abstract class ManageUserMoneyController implements IController {
    
    public static function get() {
        $page = new Page();
        $page->data['title'] = 'Geld beheren';
        
        //Redirect if user is not set
        if (!isset($_GET['user']))
            header('Location: manageuser', true, 303);
        else {
            try {
                $_SESSION['Stippers']['ManageUserMoney']['user'] = UserDB::getFullUserById($_GET['user']);
                ManageUserMoneyController::buildEnterTransactionView($page, false);
            }
            catch (UserDBException $ex) {
                if ($ex->getCode() == UserDBException::NOUSERFORCARDNUMER)
                    $page->data['ErrorMessageNoDescriptionWithLinkView']['errorTitle'] = 'Er is geen gebruiker met deze id';
                else
                    $page->data['ErrorMessageNoDescriptionWithLinkView']['errorTitle'] = 'Kan gegevens niet ophalen uit de database';
                
                $page->data['ErrorMessageNoDescriptionWithLinkView']['tryAgainUrl'] = $_SERVER['REQUEST_URI'];
                $page->addView('error/ErrorMessageNoDescriptionWithLinkView');
            }
            catch (Exception $ex) {
                $page->data['ErrorMessageNoDescriptionWithLinkView']['errorTitle'] = 'Kan gegevens niet ophalen uit de database';
                $page->data['ErrorMessageNoDescriptionWithLinkView']['tryAgainUrl'] = $_SERVER['REQUEST_URI'];
                $page->addView('error/ErrorMessageNoDescriptionWithLinkView');
            }
            $page->showWithMenu();
        }
    }
    
    public static function post() {
        $page = new Page();
        $page->data['title'] = 'Geld beheren';
        
        $errMsgs = ManageUserMoneyEnterTransactionViewValidator::validate($_POST);
                    
        if (empty($errMsgs)) {
            try {
                $incrMoney = ($_POST['increase_money'] == '' ? 0 : SafeMath::getCentsFromString($_POST['increase_money']));
                $decrMoney = ($_POST['decrease_money'] == '' ? 0 : SafeMath::getCentsFromString($_POST['decrease_money']));
                
                $fromPrize = isset($_POST['from_prize']);
                
                if (isset($_SESSION['Stippers']['browser']))
                    $executingBrowserName = BrowserDB::getBrowserById($_SESSION['Stippers']['browser']->browserId)->name;
                else
                    $executingBrowserName = null;
                if (isset($_SESSION['Stippers']['user']))
                    $executingUser = $_SESSION['Stippers']['user']->userId;
                else
                    $executingUser = null;
                
                $trans = new MoneyTransaction(null, $_SESSION['Stippers']['ManageUserMoney']['user']->userId, $_SESSION['Stippers']['ManageUserMoney']['user']->balance, $incrMoney, $decrMoney, MoneyTransactionConfig::DEFAULT_DISCOUNT_PERC, $fromPrize, null, $executingBrowserName, $executingUser);
                
                if ($trans->getBalAfter() < 0) {
                    $page->data['ErrorMessageWithDescriptionWithLinkView']['tryAgainUrl'] = $_SERVER['REQUEST_URI'];
                    $page->data['ErrorMessageWithDescriptionWithLinkView']['errorTitle'] = 'Saldo te laag';
                    $page->data['ErrorMessageWithDescriptionWithLinkView']['errorDescription'] = 'Het saldo de kaart is te laag.<br>Je komt onder nul uit.';
                    $page->addView('error/ErrorMessageWithDescriptionWithLinkView');
                }
                else {
                    MoneyTransactionDB::addTransaction($_SESSION['Stippers']['ManageUserMoney']['user'], $trans);
                    $page->data['ManageUserMoneyTransactionResultView']['balBefore'] = $trans->getBalBefore() / 100;
                    $page->data['ManageUserMoneyTransactionResultView']['balAfter'] = $trans->getBalAfter() / 100;
                    $page->data['ManageUserMoneyTransactionResultView']['discount'] = $trans->getDiscount() / 100;
                    $page->data['ManageUserMoneyTransactionResultView']['incrMoney'] = $trans->getIncrMoney() / 100;
                    $page->data['ManageUserMoneyTransactionResultView']['decrMoney'] = $trans->getDecrMoney() / 100;
                    $page->addView('manageUserMoney/ManageUserMoneyTransactionResultView');
                }
            }
            catch (Exception $ex) {
                ManageUserMoneyController::buildEnterTransactionView($page, true);
                $page->data['ManageUserMoneyEnterTransactionView']['errMsgs']['global'] = '<h2 class="error_message" id="enter_transaction_form_error_message">Kan transactie niet registreren, probeer opnieuw.</h2>';
            }
        }
        else {
            ManageUserMoneyController::buildEnterTransactionView($page, true);
            $page->data['ManageUserMoneyEnterTransactionView']['errMsgs'] = array_merge($page->data['ManageUserMoneyEnterTransactionView']['errMsgs'], $errMsgs);
        }
        
        $page->showWithMenu();
    }
    

    /**
     * Builds the view to enter the transaction
     * 
     * @param Page $page page to add the view to
     */
    private static function buildEnterTransactionView(Page $page, $enterMode) {
        $page->data['ManageUserMoneyEnterTransactionView']['errMsgs'] = ManageUserMoneyEnterTransactionViewValidator::initErrMsgs();
        $page->data['ManageUserMoneyEnterTransactionView']['enter_transaction_formAction'] = $_SERVER['REQUEST_URI'];
        $page->data['ManageUserMoneyEnterTransactionView']['fullName'] = $_SESSION['Stippers']['ManageUserMoney']['user']->getFullName();
        $page->data['ManageUserMoneyEnterTransactionView']['currentBalance'] = $_SESSION['Stippers']['ManageUserMoney']['user']->balance;
        if ($enterMode) {
            $page->data['ManageUserMoneyEnterTransactionView']['increaseMoney'] = $_POST['increase_money'];
            $page->data['ManageUserMoneyEnterTransactionView']['decreaseMoney'] = $_POST['decrease_money'];
            $page->data['ManageUserMoneyEnterTransactionView']['fromPrizeChecked'] = (isset($_POST['from_prize']) ? 'checked' : '');
        }
        else {
            $page->data['ManageUserMoneyEnterTransactionView']['increaseMoney'] = '';
            $page->data['ManageUserMoneyEnterTransactionView']['decreaseMoney'] = '';
            $page->data['ManageUserMoneyEnterTransactionView']['fromPrizeChecked'] = '';
        }
        
        $page->addView('manageUserMoney/ManageUserMoneyEnterTransactionView');
    }
}