<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Controller for the add browser page.
 */

require_once __DIR__.'/../IController.php';
require_once __DIR__.'/../../helperClasses/Page.php';
require_once __DIR__.'/../../helperClasses/random/Random.php';

require_once __DIR__.'/../../models/browser/Browser.php';
require_once __DIR__.'/../../models/browser/BrowserDB.php';
require_once __DIR__.'/../../models/browser/BrowserDBException.php';

require_once __DIR__.'/../../views/manageBrowser/AddEditBrowserTopViewValidator.php';

abstract class AddBrowserController implements IController {
    
    public static function get() {
        $page = new Page();
        $page->data['title'] = 'Browser toevoegen';
        AddBrowserController::buildAddEditBrowserTopView($page, false);
        $page->showWithMenu();
    }
    
    public static function post() {
        if (isset($_POST['add_browser'])) {
            $page = new Page();
            $page->data['title'] = 'Browser toevoegen';
            
            $errMsgs = AddEditBrowserTopViewValidator::validate($_POST);
            
            if (empty($errMsgs)) {
                try {
                    //Create new browser from entered data
                    $browser = new Browser(null, Random::getGuid(), $_POST['browser_name'], isset($_POST['can_add_renew_users']), isset($_POST['can_check_in']), isset($_POST['is_cash_register']));
                    BrowserDB::addBrowser($browser);
                    //Also set cookie
                    setcookie("stippersAuthorization", $browser->uuid, 2147483647);
                    $page->data['SuccessMessageNoDescriptionWithLinkView']['successTitle'] = 'Browser successvol toegevoegd';
                    $page->data['SuccessMessageNoDescriptionWithLinkView']['redirectUrl'] = 'managebrowser';
                    $page->addView('success/SuccessMessageNoDescriptionWithLinkView');
                }
                catch (BrowserDBException $ex) {
                    if ($ex->getCode() == BrowserDBException::BROWSERNAMEEXISTS) {
                        AddBrowserController::buildAddEditBrowserTopView($page, true);
                        $page->data['AddEditBrowserTopView']['errMsgs']['global'] = '<h2 class="error_message" id="add_edit_browser_form_error_message">Deze naam is reeds in gebruik.</h2>';
                    }
                    else {
                        AddBrowserController::buildAddEditBrowserTopView($page, true);
                    $page->data['AddEditBrowserTopView']['errMsgs']['global'] = '<h2 class="error_message" id="add_edit_browser_form_error_message">Kan browser niet toevoegen, probeer het opnieuw.</h2>';
                    }
                }
                catch (Exception $ex) {
                    AddBrowserController::buildAddEditBrowserTopView($page, true);
                    $page->data['AddEditBrowserTopView']['errMsgs']['global'] = '<h2 class="error_message" id="add_edit_browser_form_error_message">Kan browser niet toevoegen, probeer het opnieuw.</h2>';
                }
            }
            else {
                AddBrowserController::buildAddEditBrowserTopView($page, true);
                $page->data['AddEditBrowserTopView']['errMsgs'] = array_merge($page->data['AddEditBrowserTopView']['errMsgs'], $errMsgs);
            }
            
            $page->showWithMenu();
        }
        else
            header('Location: managebrowser', true, 303);
    }
    
    public static function buildAddEditBrowserTopView(Page $page, $saveMode) {
        $page->data['AddEditBrowserTopView']['add_edit_browser_formAction'] = $_SERVER['REQUEST_URI'];

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
            if (isset($_POST['is_cash_register']))
                $page->data['AddEditBrowserTopView']['isCashRegisterChecked'] = 'checked';
            else
                $page->data['AddEditBrowserTopView']['isCashRegisterChecked'] = '';
        }
        else {
            $page->data['AddEditBrowserTopView']['browserName'] = '';
            $page->data['AddEditBrowserTopView']['canAddRenewUsersChecked'] = '';
            $page->data['AddEditBrowserTopView']['canCheckInChecked'] = '';
            $page->data['AddEditBrowserTopView']['isCashRegisterChecked'] = '';
        }
        
        $page->data['AddEditBrowserTopView']['errMsgs'] = AddEditBrowserTopViewValidator::initErrMsgs();
        $page->addView('manageBrowser/AddEditBrowserTopView');
        $page->addView('manageBrowser/AddBrowserBottomView');
    }
}