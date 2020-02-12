<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Runnable to send a summary of the balance on user accounts to the receivers set in config.
 */

require_once __DIR__.'/../IRunnable.php';

require_once __DIR__.'/../../config/WeeklyWinnerConfig.php';

require_once __DIR__.'/../../models/membership/MembershipDB.php';
require_once __DIR__.'/../../models/membership/MembershipDBException.php';

require_once __DIR__.'/../../models/weeklyWinner/WeeklyWinnerDB.php';
require_once __DIR__.'/../../models/weeklyWinner/WeeklyWinnerDBException.php';

abstract class WeeklyWinnerPicker implements IRunnable {
    
    public static function run() {
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
        }
        catch (Exception $ex) {
            var_dump($ex);
        }
    }
}