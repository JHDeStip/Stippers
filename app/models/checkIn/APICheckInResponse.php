<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Model representing a response for check ins through the API.
 */

class APICheckInResponse {
    const OK = 0;
    const ALREADY_CHECKED_IN = 1;
    const MALFORMED_CARD_NUMBER = 2;
    const NO_USER_FOR_CARD_NUMBER = 3;
    const CANNOT_GET_USER_DATA = 4;
    const CANNOT_CHECK_IN = 5;
    const CANNOT_CHECK_WEEKLY_WINNER = 6;
    const CANNOT_SEND_WINNER_NOTIFICATIONS = 7;
    
    public $checkInSuccessful = false;
    public $resultCode = APICheckInResponse::OK;
    public $isWeeklyWinner = false;
    public $userFirstName;
    public $userLastName;
    public $checkInMessage;
}