<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Controller for the page to generate the winner of the week.
 */

require_once __DIR__.'/../IController.php';
require_once __DIR__.'/../../helperClasses/Page.php';

require_once __DIR__.'/../../models/weeklyWinner/WeeklyWinnerData.php';
require_once __DIR__.'/../../models/weeklyWinner/WeeklyWinnerDB.php';
require_once __DIR__.'/../../models/weeklyWinner/WeeklyWinnerDBException.php';

require_once __DIR__.'/../../models/user/User.php';
require_once __DIR__.'/../../models/user/UserDB.php';
require_once __DIR__.'/../../models/user/UserDBException.php';

require_once __DIR__.'/../../models/membership/MembershipDB.php';
require_once __DIR__.'/../../models/membership/MembershipDBException.php';

require_once __DIR__.'/../../config/WeeklyWinnerConfig.php';

abstract class WeeklyWinnerController implements IController {
    
    public static function get() {
        $page = new Page();
        $page->data['title'] = 'Winnaar van de week';
        $page->addView('weeklyWinner/WeeklyWinnerTopView');
        try {
            $weeklyWinnerData = WeeklyWinnerDB::getThisWeeksWinnerData();
            
            //If there's already a winner we show it's data
            if ($weeklyWinnerData) {
                $user = UserDB::getBasicUserById($weeklyWinnerData->userId);
                $page->data['WeeklyWinnerShowWinnerView']['winnerFullName'] = $user->getFullName();
                $page->data['WeeklyWinnerShowWinnerView']['hasCollectedPrize'] = $weeklyWinnerData->hasCollectedPrize;
                $page->addView('weeklyWinner/WeeklyWinnerShowWinnerView');
            }
            //Else we show the form to generate a new one
            else {
                $page->data['WeeklyWinnerGenerateFormView']['generate_winner_formAction'] = $_SERVER['REQUEST_URI'];
                $page->addView('weeklyWinner/WeeklyWinnerGenerateFormView');
            }
        }
        catch (Exception $ex) {
            $page->data['ErrorMessageNoDescriptionNoLinkView']['errorTitle'] = 'Kon winnaar niet ophalen';
            $page->addView('error/ErrorMessageNoDescriptionNoLinkView');
        }
        
        $page->showWithMenu();
    }
    
    public static function post() {
        try {
            //Get list of user IDs of users that are a member this year
            $userIds = MembershipDB::getUserIdsThisYear();
			
            //Get list of last N winners
            $lastWinners = WeeklyWinnerDB::getLastNWinners(WeeklyWinnerConfig::MIN_WINNING_INTERVAL);
            
            //Get list of user IDs that have not won the last N times
            if ($lastWinners)
                $possibleWinners = array_values(array_diff($userIds, $lastWinners));
            else
                $possibleWinners = $userIds;

            //Pick a random user ID
            $winnerIdx = rand(0, count($possibleWinners) - 1);
			
            //Add chosen user ID as winner
            WeeklyWinnerDB::addWeeklyWinner($possibleWinners[$winnerIdx]);
            
            //Show the page again, this is the same as GETing so we just call get
            WeeklyWinnerController::get();
        }
        catch (Exception $ex){
            $page = new Page();
            $page->addView('weeklyWinner/WeeklyWinnerTopView');
            $page->data['ErrorMessageNoDescriptionWithLinkView']['errorTitle'] = 'Kan geen nieuwe winnaar loten.';
            $page->data['ErrorMessageNoDescriptionWithLinkView']['tryAgainUrl'] = $_SERVER['REQUEST_URI'];
            $page->addView('error/ErrorMessageNoDescriptionWithLinkView');
            $page->showWithMenu();
        }
    }
}