<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Controller for the check in api call.
 */

require_once __DIR__.'/../IAPIController.php';
require_once __DIR__.'/../../helperClasses/Page.php';

require_once __DIR__.'/../../config/DataValidationConfig.php';

require_once __DIR__.'/../../helperClasses/email/Email.php';
require_once __DIR__.'/../../helperClasses/email/EmailException.php';

require_once __DIR__.'/../../models/user/User.php';
require_once __DIR__.'/../../models/user/UserDB.php';
require_once __DIR__.'/../../models/user/UserDBException.php';

require_once __DIR__.'/../../models/weeklyWinner/WeeklyWinnerData.php';
require_once __DIR__.'/../../models/weeklyWinner/WeeklyWinnerDB.php';
require_once __DIR__.'/../../models/weeklyWinner/WeeklyWinnerDBException.php';

require_once __DIR__.'/../../models/checkIn/APICheckInResponse.php';
require_once __DIR__.'/../../models/checkIn/CheckInDB.php';
require_once __DIR__.'/../../models/checkIn/CheckInDBException.php';

abstract class CheckInController implements IAPIController {
	
    public static function get() {
        //Check if the card number is given
        if (!isset($_GET['cardNumber']) || !(is_numeric($_GET['cardNumber']) && (int)$_GET['cardNumber'] == $_GET['cardNumber'])) {
	    header('HTTP/1.1 400 Bad Request');
	    exit();
	}
            
        //Create response object
        $response = new APICheckInResponse();
		
		$cardNumber = (int)$_GET['cardNumber'];
        
        //Check if the card number is valid
        if (!CheckInController::validateCardNumber($cardNumber)) {
            $response->resultCode = APICheckInResponse::MALFORMED_CARD_NUMBER;
            echo json_encode($response);
            exit();
        }
        else {
            $user = null;
            
            $checkInOk = false;
            $weeklyWinnerOk = true;
            
            //Get user to check in
            try {
                //Get the user who's card number for this year was entered
                $user = UserDB::getFullUserByCardNumber($cardNumber);
            }
            catch (Exception $ex) {
                $response->resultCode = APICheckInResponse::CANNOT_GET_USER_DATA;
                echo json_encode($response);
                exit();
            }
            
            if (!$user) {
                //There's no user for this card
                $response->resultCode = APICheckInResponse::NO_USER_FOR_CARD_NUMBER;
                echo json_encode($response);
                exit();
            }
            else {
                //We have a user so get the names in the response
                $response->userFirstName = $user->firstName;
                $response->userLastName = $user->lastName;
                $response->checkInMessage = $user->checkInMessage;
                
                //Check user in
                try {
                    $checkInOk = CheckInDB::checkIn($user->userId);
                }
                catch (Exception $ex) {
                    //Check-in failed (something went wrong or check-in isn't valid)
                    $response->resultCode = APICheckInResponse::CANNOT_CHECK_IN;
                    echo json_encode($response);
                    exit();
                }
            }
            
            if (!$checkInOk) {
                //The user has already checked in
                $response->resultCode = APICheckInResponse::ALREADY_CHECKED_IN;
                echo json_encode($response);
                exit();
            }
            else {
                $response->checkInSuccessful = true;
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
                    $response->resultCode = APICheckInResponse::CANNOT_CHECK_WEEKLY_WINNER;
                    echo json_encode($response);
                    exit();
                }
            
                $response->isWeeklyWinner = $isWinner;

                //If he is the winner send an email to all usermanagers
                if ($isWinner) {
                    try {
                        $select = array('email' => true);
                        $searchFilter = array('isUserManager' => true);
                        $searchUsers = UserDB::getSearchUsers($select, $searchFilter, null);
                        
                        $extras['common']['winnerFirstName'] = $user->firstName;
                        $extras['common']['winnerLastName'] = $user->lastName;
								
				
                        $failedAddresses = Email::sendEmails('WeeklyWinnerNotification.html', 'Winnaar van de week', EmailConfig::FROM_ADDRESS, array_column($searchUsers, 'user'), $extras);
						
                        if (!empty($failedAddresses)) {
                            $response->resultCode = APICheckInResponse::CANNOT_SEND_WINNER_NOTIFICATIONS;
                        }
                        
                        echo json_encode($response);
                        exit();
                    }
                    catch (Exception $ex) {
                        $response->resultCode = APICheckInResponse::CANNOT_SEND_WINNER_NOTIFICATIONS;
                        echo json_encode($response);
                        exit();
                    }
                }
                //If he is not the winner show the normal check in successful view
                else {
                    echo json_encode($response);
                    exit();
                }
            }
        }
    }
    
    public static function post() {
        header('HTTP/1.1 405 Method Not Allowed');
    }
    
    public static function put() {
        header('HTTP/1.1 405 Method Not Allowed');
    }
    
    public static function delete() {
        header('HTTP/1.1 405 Method Not Allowed');
    }
    
    private static function validateCardNumber($cardNumber) {
        return $cardNumber > 0 && $cardNumber <= 99999999;
    }
}