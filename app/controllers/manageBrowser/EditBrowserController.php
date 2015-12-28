<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Controller for the edit browser page.
 */

require_once __DIR__.'/../IController.php';
require_once __DIR__.'/../../helperClasses/Page.php';
require_once __DIR__.'/../../helperClasses/random/Random.php';

require_once __DIR__.'/../../models/browser/Browser.php';
require_once __DIR__.'/../../models/browser/BrowserDB.php';
require_once __DIR__.'/../../models/browser/BrowserDBException.php';

require_once __DIR__.'/../../views/manageBrowser/AddEditBrowserTopViewValidator.php';

abstract class EditBrowserController implements IController {
    
    public static function get() {
        if (!isset($_GET['browser'])) {
            //No browser id given so redirect to the manageuser
            //page to search a user
            header('Location: managebrowser', true, 303);
        }
        else {
            $page = new Page();
            $page->data['title'] = 'Browser bewerken';
            
            try {
                //Load requested browser in session
                $_SESSION['Stippers']['EditBrowser']['browser'] = BrowserDB::getBrowserById($_GET['browser']);
                EditBrowserController::buildAddEditBrowserTopView($page, false);
            }
            catch (BrowserDBException $ex) {
                if ($ex->getCode() == BrowserDBException::NOBROWSERFORID)
                    $page->data['ErrorMessageNoDescriptionWithLinkView']['errorTitle'] = 'Er is geen browser met deze id';
                else
                    $page->data['ErrorMessageNoDescriptionWithLinkView']['errorTitle'] = 'Kan browser niet ophalen';
                $page->data['ErrorMessageNoDescriptionWithLinkView']['tryAgainUrl'] = 'managebrowser';
                $page->addView('error/ErrorMessageNoDescriptionWithLinkView');
            }
            catch (Exception $ex) {
                $page->data['ErrorMessageNoDescriptionWithLinkView']['errorTitle'] = 'Kan browser niet ophalen';
                $page->data['ErrorMessageNoDescriptionWithLinkView']['tryAgainUrl'] = 'managebrowser';
                $page->addView('error/ErrorMessageNoDescriptionWithLinkView');
            }
            
            $page->showWithMenu();
        }
    }
    
    public static function post() {
        $page = new Page();
        $page->data['title'] = 'Browser bewerken';
        
        if (isset($_POST['save'])) {
            $errMsgs = AddEditBrowserTopViewValidator::validate($_POST);
            
            if (empty($errMsgs)) {
                try {
                    //Create new browser of entered data and try to update
                    $newBrowser = new Browser($_SESSION['Stippers']['EditBrowser']['browser']->browserId, Random::getGuid(), $_POST['browser_name'], isset($_POST['can_add_renew_users']), isset($_POST['can_check_in']));
                    BrowserDB::updateBrowser($_SESSION['Stippers']['EditBrowser']['browser'], $newBrowser);
                    $page->data['SuccessMessageNoDescriptionWithLinkView']['successTitle'] = 'Browser successvol gewijzigd';
                    $page->data['SuccessMessageNoDescriptionWithLinkView']['redirectUrl'] = 'managebrowser';
                    $page->addView('success/SuccessMessageNoDescriptionWithLinkView');
                }
                catch (BrowserDBException $ex) {
                    if ($ex->getCode() == BrowserDBException::BROWSEROUTOFDATE) {
                        $page->data['ErrorMessageWithDescriptionWithLinkView']['errorTitle'] = 'Gebruiker niet hernieuwd';
                        $page->data['ErrorMessageWithDescriptionWithLinkView']['errorDescription'] = 'Iemand anders heeft de gebruiker in tussentijd al gewijzigd.';
                        $page->data['ErrorMessageWithDescriptionWithLinkView']['tryAgainUrl'] = $_SERVER['REQUEST_URI'];
                        $page->addView('error/ErrorMessageWithDescriptionWithLinkView');
                    }
                    else {
                        EditBrowserController::buildAddEditBrowserTopView($page, true);
                        if ($ex->getCode() == BrowserDBException::BROWSERNAMEEXISTS)
                            $page->data['AddEditBrowserTopView']['errMsgs']['global'] = '<h2 class="error_message" id="add_edit_browser_form_error_message">Deze naam is reeds in gebruik.</h2>';
                        else
                            $page->data['AddEditBrowserTopView']['errMsgs']['global'] = '<h2 class="error_message" id="add_edit_browser_form_error_message">Kan browser niet wijzigen, probeer het opnieuw.</h2>';
                    }
                }
                catch (Exception $ex) {
                    EditBrowserController::buildAddEditBrowserTopView($page, true);
                    $page->data['AddEditBrowserTopView']['errMsgs']['global'] = '<h2 class="error_message" id="add_edit_browser_form_error_message">Kan browser niet wijzigen, probeer het opnieuw.</h2>';
                }
            }
            else {
                EditBrowserController::buildAddEditBrowserTopView($page, true);
                $page->data['AddEditBrowserTopView']['errMsgs'] = array_merge($page->data['AddEditBrowserTopView']['errMsgs'], $errMsgs);
            }
            
            $page->showWithMenu();
        }
        elseif (isset($_POST['delete'])) {
            try {
                BrowserDB::removeBrowser($_SESSION['Stippers']['EditBrowser']['browser']);
                $page->data['SuccessMessageNoDescriptionWithLinkView']['successTitle'] = 'Browser successvol gewijzigd';
                $page->data['SuccessMessageNoDescriptionWithLinkView']['redirectUrl'] = 'managebrowser';
                $page->addView('success/SuccessMessageNoDescriptionWithLinkView');
            }
            catch (BrowserDBException $ex) {
                if ($ex->getCode() == BrowserDBException::BROWSEROUTOFDATE) {
                    $page->data['ErrorMessageWithDescriptionWithLinkView']['errorTitle'] = 'Gebruiker niet hernieuwd';
                    $page->data['ErrorMessageWithDescriptionWithLinkView']['errorDescription'] = 'Iemand anders heeft de gebruiker in tussentijd al gewijzigd.';
                    $page->data['ErrorMessageWithDescriptionWithLinkView']['tryAgainUrl'] = $_SERVER['REQUEST_URI'];
                    $page->addView('error/ErrorMessageWithDescriptionWithLinkView');
                }
                else {
                    EditBrowserController::buildAddEditBrowserTopView($page, true);
                    $page->data['AddEditBrowserTopView']['errMsgs']['global'] = '<h2 class="error_message" id="add_edit_browser_form_error_message">Kan browser niet wijzigen, probeer het opnieuw.</h2>';
                }
            }
            catch (Exception $ex) {
                EditBrowserController::buildAddEditBrowserTopView($page, true);
                $page->data['AddEditBrowserTopView']['errMsgs']['global'] = '<h2 class="error_message" id="add_edit_browser_form_error_message">Kan browser niet wijzigen, probeer het opnieuw.</h2>';
            }
                
            $page->showWithMenu();
        }
        else
            header('Location: managebrowser', true, 303);
    }
    
    public static function buildAddEditBrowserTopView(Page $page, $saveMode) {
        $page->data['AddEditBrowserTopView']['add_edit_browser_formAction'] = $_SERVER['REQUEST_URI'];
        $page->data['AddEditBrowserTopView']['errMsgs'] = AddEditBrowserTopViewValidator::initErrMsgs();
        
        if ($saveMode) {
            $page->data['AddEditBrowserTopView']['browserName'] = $_POST['browser_name'];
            if (isset($_POST['can_add_renew_users']))
                $page->data['AddEditBrowserTopView']['canAddRenewUsersChecked'] = 'checked';
            else
                $page->data['AddEditBrowserTopView']['canAddRenewUsersChecked'] = '';
            if (isset($_POST['can_check_in']))
                $page->data['AddEditBrowserTopView']['canCheckInChecked'] = 'checked';
            else
                $page->data['AddEditBrowserTopView']['canCheckInChecked'] = '';
        }
        else {
            $page->data['AddEditBrowserTopView']['browserName'] = $_SESSION['Stippers']['EditBrowser']['browser']->name;
            if ($_SESSION['Stippers']['EditBrowser']['browser']->canAddRenewUsers)
                $page->data['AddEditBrowserTopView']['canAddRenewUsersChecked'] = 'checked';
            else
                $page->data['AddEditBrowserTopView']['canAddRenewUsersChecked'] = '';
            if ($_SESSION['Stippers']['EditBrowser']['browser']->canCheckIn)
                $page->data['AddEditBrowserTopView']['canCheckInChecked'] = 'checked';
            else
                $page->data['AddEditBrowserTopView']['canCheckInChecked'] = '';
        }
    
        $page->addView('manageBrowser/AddEditBrowserTopView');
        $page->addView('manageBrowser/EditBrowserBottomView');
        $page->data['AddEditBrowserTopView']['errMsgs'] = AddEditBrowserTopViewValidator::initErrMsgs();
    }
}