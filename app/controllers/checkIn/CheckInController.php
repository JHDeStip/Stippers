<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Controller for the check-in page.
 */

require_once __DIR__.'/../IController.php';
require_once __DIR__.'/../../helperClasses/Page.php';

require_once __DIR__.'/../../helperClasses/email/Email.php';
require_once __DIR__.'/../../helperClasses/email/EmailException.php';

require_once __DIR__.'/../../models/user/User.php';
require_once __DIR__.'/../../models/user/UserDB.php';
require_once __DIR__.'/../../models/user/UserDBException.php';

require_once __DIR__.'/../../models/checkIn/CheckInDB.php';
require_once __DIR__.'/../../models/checkIn/CheckInDBException.php';

require_once __DIR__.'/../../models/weeklyWinner/WeeklyWinnerData.php';
require_once __DIR__.'/../../models/weeklyWinner/WeeklyWinnerDB.php';
require_once __DIR__.'/../../models/weeklyWinner/WeeklyWinnerDBException.php';

require_once __DIR__.'/../../views/checkIn/CheckInFormViewValidator.php';

abstract class CheckInController implements IController {
    
    public static function get() {
        $page = new Page();
        $page->data['title'] = 'Inchecken';
        CheckInController::buildCheckInFormView($page, false);
        $page->showWithMenu();
    }
    
    public static function post() {
        $page = new Page();
        $page->data['title'] = 'Inchecken';
        
        $errMsgs = CheckInFormViewValidator::validate($_POST);
        $user = null;
        
        if (empty($errMsgs)) {
            $checkInOk = false;
            $weeklyWinnerOk = true;
            
            //Get user to check in
            try {
                //Get the user who's card number for this year was entered
                $user = UserDB::getBasicUserByCardNumber($_POST['card_number']);
            }
            catch (Exception $ex) {
                //Check-in failed (can't get user)
                CheckInController::buildCheckInFormView($page, true);
                $page->data['CheckInFormView']['errMsgs']['global'] = '<h2 class="error_message" id="check_in_form_error_message">Kan gebruiker niet inchecken, probeer het opnieuw.</h2>';
                $page->showWithMenu();
                exit();
            }
            
            if (!$user) {
                //There's no user for this card
                CheckInController::buildCheckInFormView($page, true);
                $page->data['CheckInFormView']['errMsgs']['global'] = '<h2 class="error_message" id="check_in_form_error_message">Dit kaartnummer is niet gekoppeld aan een gebruiker.</h2>';
                $page->showWithMenu();
                exit();
            }
            else {
                //Check user in
                try {
                    $checkInOk = CheckInDB::checkIn($user->userId);
                }
                catch (Exception $ex) {
                    //Check-in failed (something went wrong or check-in isn't valid)
                    CheckInController::buildCheckInFormView($page, true);
                    $page->data['CheckInFormView']['errMsgs']['global'] = '<h2 class="error_message" id="check_in_form_error_message">Kan gebruiker niet inchecken, probeer het opnieuw.</h2>';
                    $page->showWithMenu();
                    exit();
                }
            }
            
            if (!$checkInOk) {
                //The user has already checked in
                CheckInController::buildCheckInFormView($page, true);
                $page->data['CheckInFormView']['errMsgs']['global'] = '<h2 class="error_message" id="check_in_form_error_message">Deze gebruiker is de voorbije 12 uur al ingechecked.</h2>';
                $page->showWithMenu();
                exit();
            }
            else {
                //Check in successful, check whether he is the winner of the week
                $isWinner = false;
                
                try {
                    //Check if this user is the winner of the week
                    $weeklyWinnerData = WeeklyWinnerDB::getThisWeeksWinnerData();
                    $isWinner = $weeklyWinnerData && $weeklyWinnerData->userId == $user->userId && !$weeklyWinnerData->hasCollectedPrize;
                    //If he is the winner we set in the database that the user collected his prize
                    if ($isWinner) {
                        $newWeeklyWinnerData = new WeeklyWinnerData($weeklyWinnerData->startOfWeek, $weeklyWinnerData->userId, true);
                        WeeklyWinnerDB::updateWeeklyWinnerData($weeklyWinnerData, $newWeeklyWinnerData);
                    }
                }
                catch (Exception $ex) {
                    $weeklyWinnerOk = false;
                }
                
                //If he is the winner, add the winner views and try to send an email to all usermanagers
                if ($isWinner) {
                    $page->addView('checkIn/CheckInWeeklyWinnerTitleView');
                    CheckInController::buildCheckInSuccessfulView($page, $user);
                    try {
                        $select = array('email' => true);
                        $searchFilter = array('isUserManager' => true);
                        $searchUsers = UserDB::getSearchUsers($select, $searchFilter, null);
                        
                        $extras['common']['winnerFirstName'] = $user->firstName;
                        $extras['common']['winnerLastName'] = $user->lastName;
                        
                        $failedAddresses = Email::sendEmails('WeeklyWinnerNotification', 'Winnaar van de week', EmailConfig::FROM_ADDRESS, array_column($searchUsers, 'user'), $extras);
                        
                        if (!empty($failedAddresses)) {
                            $page->data['ErrorMessageWithDescriptionNoLinkView']['errorTitle'] = 'Kan e-mail niet versturen.';
                            $page->data['ErrorMessageWithDescriptionNoLinkView']['description'] = 'Kan geen e-mail versturen naar het bestuur om te laten weten dat je gewonnen hebt. Meld dit even aan een tapper of bestuurslid.';
                            $page->addView('error/ErrorMessageWithDescriptionNoLinkView');
                        }
                    }
                    catch (Exception $ex) {
                        $page->data['ErrorMessageWithDescriptionNoLinkView']['errorTitle'] = 'Kan e-mail niet versturen.';
                        $page->data['ErrorMessageWithDescriptionNoLinkView']['errorDescription'] = 'Kan geen e-mail versturen naar het bestuur om te laten weten dat je gewonnen hebt. Meld dit even aan een tapper of bestuurslid.';
                        $page->addView('error/ErrorMessageWithDescriptionNoLinkView');
                    }
                    $page->addView('checkIn/CheckInWeeklyWinnerImageView');
                    $page->showWithMenu();
                }
                //If he is not the winner show the normal check in successful view
                else {
                    $page->addView('checkIn/CheckInSuccessfulTitleView');
                    CheckInController::buildCheckInSuccessfulView($page, $user);
                    
                    if (!$weeklyWinnerOk) {
                        $page->data['ErrorMessageNoDescriptionNoLinkView']['errorTitle'] = 'Kan niet controleren of je de winnaar van de week bent.';
                        $page->addView('error/ErrorMessageNoDescriptionNoLinkView');
                    }
                    else {
                        $page->addExtraJsFile('views/checkIn/CheckInRedirector.js');
                        $page->addExtraJsFile('views/checkIn/checkInSuccessfulOnLoadHandler.js');
                    }
                    $page->showWithMenu();
                }
            }   
        }
        else {
            //Errors in the form, retry
            CheckInController::buildCheckInFormView($page, true);
            $page->data['CheckInFormView']['errMsgs'] = array_merge($page->data['CheckInFormView']['errMsgs'], $errMsgs);
            $page->showWithMenu();
        }
    }
    
    /**
     * Builds the check-in form view
     * 
     * @param Page $page page to add the view to
     */
    private static function buildCheckInFormView(Page $page, $checkInMode) {
        $page->data['CheckInFormView']['check_in_formAction'] = $_SERVER['REQUEST_URI'];
        
        if($checkInMode)
            $page->data['CheckInFormView']['cardNumber'] = $_POST['card_number'];
        else
            $page->data['CheckInFormView']['cardNumber'] = '';
        
        $page->data['CheckInFormView']['errMsgs'] = CheckInFormViewValidator::initErrMsgs();
        $page->addView('checkIn/CheckInFormView');
        $page->addExtraJsFile('barcodeScanner/BarcodeScanner.js');
        $page->addExtraJsFile('views/checkIn/CheckInBarcodeScanner.js');
        $page->addExtraJsFile('views/checkIn/checkInFormOnLoadHandler.js');
    }
    
    /**
     * Builds the view for the successful check-in message
     * 
     * @param Page $page page to add the view to
     * @param User $user user to get the name from
     */
    private static function buildCheckInSuccessfulView(Page $page, User $user) {
        $page->data['CheckInSuccessfulView']['fullName'] = $user->getFullName();
        $page->data['CheckInSuccessfulView']['redirectUrl'] = $_SERVER['REQUEST_URI'];
        $page->addView('checkIn/CheckInSuccessfulView');
    }
}