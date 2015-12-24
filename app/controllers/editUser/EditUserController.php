<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Controller for the edit user page.
 */

require_once __DIR__.'/../IController.php';
require_once __DIR__.'/../../helperClasses/Page.php';

require_once __DIR__.'/../../models/user/User.php';
require_once __DIR__.'/../../models/user/UserDB.php';
require_once __DIR__.'/../../models/user/UserDBException.php';

require_once __DIR__.'/../../models/membership/MembershipDB.php';
require_once __DIR__.'/../../models/membership/MembershipDBException.php';

require_once __DIR__.'/../../models/checkIn/CheckInDB.php';
require_once __DIR__.'/../../models/checkIn/CheckInDBException.php';

require_once __DIR__.'/../../views/EditUser/EditUserTopViewValidator.php';

abstract class EditUserController implements IController {
    
    public static function get() {
        if (!isset($_GET['user'])) {
            //No user id given so redirect to the manageuser
            //page to search a user
            header('Location: manageuser', TRUE, 303);
        }
        else {
            $page = new Page();
            $page->data['title'] = 'Gebruiker bewerken';
            try {
                $_SESSION['Stippers']['EditUser']['user'] = UserDB::getFullUserById($_GET['user']);
                EditUserController::prepUserSearchViews($page, false);
                try {
                    EditUserController::prepEditUserMembershipDetailsViewData($page);
                    $page->addView('editUser/EditUserMemberShipDetailsView');
                }
                catch (Exception $ex) {
                    $page->data['ErrorNoDescriptionWithLinkView']['errorTitle'] = 'Kan gegevens niet ophalen uit de database.';
                    $page->data['ErrorNoDescriptionWithLinkView']['tryAgainUrl'] = $_SERVER['REQUEST_URI'];
                    $page->addView('error/ErrorNoDescriptionWithLinkView');
                }
                
            }
            catch (Exception $ex) {
                $page->data['ErrorNoDescriptionWithLinkView']['errorTitle'] = 'Kan gegevens niet ophalen uit de database.';
                $page->data['ErrorNoDescriptionWithLinkView']['tryAgainUrl'] = $_SERVER['REQUEST_URI'];
                $page->addView('error/ErrorNoDescriptionWithLinkView');
            }
            $page->showBasic();
        }
    } 
    
    public static function post() {
        
    }
    
    private static function prepUserSearchViews($page, $editMode) {
        EditUserController::prepEditUserTopViewData($page, $editMode);
        $page->addView('editUser/EditUserTopView');
        $page->data['EditUserTopView']['errMsgs'] = EditUserTopViewValidator::initErrMsgs();
        if ($_SESSION['Stippers']['user']->isAdmin) {
            EditUserController::prepEditUserAdminViewData($page, $editMode);
            $page->addView('editUser/EditUserAdminView');
        }
        if ($editMode)
            $page->addView('editUser/EditUserEnabledFormBottomView');
        else
            $page->addView('editUser/EditUserDisabledFormBottomView');
        EditUserController::prepEditUserMembershipDetailsViewData($page);
        $page->addView('editUser/EditUserMembershipDetailsView');
    }
    
    private static function prepEditUserTopViewData($page, $editMode) {
        $page->data['EditUserTopView']['edit_user_formAction'] = $_SERVER['REQUEST_URI'];
        
        $page->data['EditUserTopView']['email'] = $_SESSION['Stippers']['EditUser']['user']->email;
        $page->data['EditUserTopView']['firstName'] = $_SESSION['Stippers']['EditUser']['user']->firstName;
        $page->data['EditUserTopView']['lastName'] = $_SESSION['Stippers']['EditUser']['user']->lastName;
        $page->data['EditUserTopView']['street'] = $_SESSION['Stippers']['EditUser']['user']->street;
        $page->data['EditUserTopView']['houseNumber'] = $_SESSION['Stippers']['EditUser']['user']->houseNumber;
        $page->data['EditUserTopView']['city'] = $_SESSION['Stippers']['EditUser']['user']->city;
        $page->data['EditUserTopView']['postalCode'] = $_SESSION['Stippers']['EditUser']['user']->postalCode;
        $page->data['EditUserTopView']['country'] = $_SESSION['Stippers']['EditUser']['user']->country;
        $page->data['EditUserTopView']['phone'] = $_SESSION['Stippers']['EditUser']['user']->phone;
        $page->data['EditUserTopView']['dateOfBirth'] = $_SESSION['Stippers']['EditUser']['user']->dateOfBirth;
        $page->data['EditUserTopView']['creationTime'] = $_SESSION['Stippers']['EditUser']['user']->creationTime;
        $page->data['EditUserTopView']['balance'] = $_SESSION['Stippers']['EditUser']['user']->balance;
        
        $page->data['EditUserTopView']['disabled'] = ($editMode ? '' : 'disabled');
    }
    
    private static function prepEditUserAdminViewData($page, $editMode) {
        $page->data['EditUserAdminView']['isAdminChecked'] = ($_SESSION['Stippers']['EditUser']['user']->isAdmin ? 'checked' : '');
        $page->data['EditUserAdminView']['isUserManagerChecked'] = ($_SESSION['Stippers']['EditUser']['user']->isUserManager ? 'checked' : '');
        $page->data['EditUserAdminView']['isBrowserManagerChecked'] = ($_SESSION['Stippers']['EditUser']['user']->isBrowserManager ? 'checked' : '');
        
        $page->data['EditUserAdminView']['disabled'] = ($editMode ? '' : 'disabled');
    }
    
    private static function prepEditUserMembershipDetailsViewData($page) {
        $page->data['EditUserMembershipDetailsView']['membershipYearDetails'] = MembershipDB::getUserMembershipDetailsByUserId($_SESSION['Stippers']['EditUser']['user']->userId);
        $page->data['EditUserMembershipDetailsView']['totalCheckIns'] = CheckInDB::getTotalCheckInsByUserId($_SESSION['Stippers']['EditUser']['user']->userId);
    }
    
    private static function validateEditUserViewsData(){}
}