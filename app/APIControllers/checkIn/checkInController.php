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
        header('HTTP/1.1 405 Method Not Allowed');
    }
    
    public static function post() {
        //Get the request body as json
        $checkInRequest = json_decode(file_get_contents('php://input'));
        
        //Check if the json was valid and a card number is given
        if (!$checkInRequest || !isset($checkInRequest->cardNumber))
            header('HTTP/1.1 400 Bad Request');
        else {
            //Create response object
            $response = new APICheckInResponse();
            
            //Check if the card number is valid
            if (!CheckInController::validateCardNumber($checkInRequest->cardNumber))
                $response->errorCode = APICheckInResponse::MALFORMED_CARDNUMBER;
            else {
                $user = null;
                
                $checkInOk = false;
                $weeklyWinnerOk = true;
                
                //Get user to check in
                try {
                    //Get the user who's card number for this year was entered
                    $user = UserDB::getBasicUserByCardNumber($checkInRequest->cardNumber);
                }
                catch (Exception $ex) {
                    $response->checkInSuccessful = false;
                    $response->errorCode = APICheckInResponse::CANNOT_GET_USER_DATA;
                    echo json_encode($response);
                    exit();
                }
                
                if (!$user) {
                    //There's no user for this card
                    $response->checkInSuccessful = false;
                    $response->errorCode = APICheckInResponse::NO_USER_FOR_CARD_NUMBER;
                    echo json_encode($response);
                    exit();
                }
                else {
                    //We have a user so get the names in the response
                    $response->userFirstName = $user->firstName;
                    $response->userLastName = $user->lastName;
                    
                    //Check user in
                    try {
                        $checkInOk = CheckInDB::checkIn($user->userId);
                    }
                    catch (Exception $ex) {
                        //Check-in failed (something went wrong or check-in isn't valid)
                        $response->checkInSuccessful = false;
                        $response->errorCode = APICheckInResponse::CANNOT_CHECK_IN;
                        echo json_encode($response);
                        exit();
                    }
                }
                
                if (!$checkInOk) {
                    //The user has already checked in
                    $response->checkInSuccessful = false;
                    $response->errorCode = APICheckInResponse::ALREADY_CHECKED_IN;
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
                        $response->errorCode = APICheckInResponse::CANNOT_CHECK_WEEKLY_WINNER;
                        echo json_encode($response);
                        exit();
                    }
                
                    $response->isWeeklyWinner = $isWinner;
                    //If he is the winner, add the winner views and try to send an email to all usermanagers
                    if ($isWinner) {
                        try {
                            $select = array('email' => true);
                            $searchFilter = array('isUserManager' => true);
                            $searchUsers = UserDB::getSearchUsers($select, $searchFilter, null);
                            
                            $extras['common']['winnerFirstName'] = $user->firstName;
                            $extras['common']['winnerLastName'] = $user->lastName;
                            
                            $failedAddresses = Email::sendEmails('WeeklyWinnerNotification.html', 'Winnaar van de week', EmailConfig::FROM_ADDRESS, array_column($searchUsers, 'user'), $extras);
                        
                            if (!empty($failedAddresses)) {
                                $response->errorCode = APICheckInResponse::CANNOT_SEND_WINNER_NOTIFICATIONS;
                                echo json_encode($response);
                                exit();
                            }
                        }
                        catch (Exception $ex) {
                            $response->errorCode = APICheckInResponse::CANNOT_SEND_WINNER_NOTIFICATIONS;
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
    }
    
    public static function put() {
        header('HTTP/1.1 405 Method Not Allowed');
    }
    
    public static function delete() {
        header('HTTP/1.1 405 Method Not Allowed');
    }
    
    private static function validateCardNumber($cardNumber) {
        return preg_match('/^[0-9]{1,'.DataValidationConfig::CARD_NUMBER_MAX_LENGTH.'}$/', $cardNumber);
    }
}