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
            try {
                //Get the user who's card number for this year was entered
                $user = UserDB::getBasicUserByCardNumber($_POST["card_number"]);
                //Check the user in
                CheckInDB::checkIn($user->userId);
                
                //Check if this user is the winner of the week
                $weeklyWinnerData = WeeklyWinnerDB::getThisWeeksWinnerData();
                $isWinner = $weeklyWinnerData && $weeklyWinnerData->userId == $user->userId && !$weeklyWinnerData->hasCollectedPrize;
                //If he is the winner we set in the database that the user collected his prize
                if ($isWinner) {
                    $newWeeklyWinnerData = new WeeklyWinnerData($weeklyWinnerData->startOfWeek, $weeklyWinnerData->userId, true);
                    WeeklyWinnerDB::updateWeeklyWinnerData($weeklyWinnerData, $newWeeklyWinnerData);
                }
                
                //Build the page
                if ($isWinner)
                    $page->addView('checkIn/CheckInWeeklyWinnerTitleView');
                else
                    $page->addView('checkIn/CheckInSuccessfulTitleView');
                
                CheckInController::buildCheckInSuccessfulView($page, $user);
                
                if ($isWinner)
                    $page->addView('checkIn/CheckInWeeklyWinnerImageView');                
            }
            catch (UserDBException $ex) {
                //Check-in failed (can't get user)
                CheckInController::buildCheckInFormView($page, true);
                if ($ex->getCode() == UserDBException::NOUSERFORCARDNUMER)
                    $page->data['CheckInFormView']['errMsgs']['global'] = '<h2 class="error_message" id="check_in_form_error_message">Dit kaartnummer is niet gekoppeld aan een gebruiker.</h2>';
                else
                    $page->data['CheckInFormView']['errMsgs']['global'] = '<h2 class="error_message" id="check_in_form_error_message">Kan gebruiker niet inchecken, probeer het opnieuw.</h2>';
            }
            catch (CheckInDBException $ex) {
                //Check-in failed (something went wrong or check-in isn't valid)
                CheckInController::buildCheckInFormView($page, true);
                if ($ex->getCode() == CheckInDBException::ALREADYCHECKEDIN)
                    $page->data['CheckInFormView']['errMsgs']['global'] = '<h2 class="error_message" id="check_in_form_error_message">Deze gebruiker is de voorbije 12 uur al ingechecked.</h2>';
                else
                    $page->data['CheckInFormView']['errMsgs']['global'] = '<h2 class="error_message" id="check_in_form_error_message">Kan gebruiker niet inchecken, probeer het opnieuw.</h2>';
            }
            catch (WeeklyWinnerDBException $ex) {
                //Can't check/update weekly winner data, but check-in succeeded
                $page->addView('checkIn/CheckInSuccessfulTitleView');
                CheckInController::buildCheckInSuccessfulView($page, $user);
                
                $page->data['ErrorMessageNoDescriptionNoLinkView']['errorTitle'] = 'Kan niet controleren of je de winnaar van de week bent.';
                $page->addView('error/ErrorMessageNoDescriptionNoLinkView');
            }
            catch (Exception $ex) {
                //Something else went wrong
                CheckInController::buildCheckInFormView($page, true);
                $page->data['CheckInFormView']['errMsgs']['global'] = '<h2 class="error_message" id="check_in_form_error_message">Kan gebruiker niet inchecken, probeer het opnieuw.</h2>';
            }
        }
        else {
            //Errors in the form, retry
            CheckInController::buildCheckInFormView($page, true);
            $page->data['CheckInFormView']['errMsgs'] = array_merge($page->data['CheckInFormView']['errMsgs'], $errMsgs);
        }
        
        $page->showWithMenu();
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
        $page->addExtraJsFile('views/checkIn/CheckInRedirector.js');
        $page->addExtraJsFile('views/checkIn/checkInSuccessFulOnLoadHandler.js');
    }
}