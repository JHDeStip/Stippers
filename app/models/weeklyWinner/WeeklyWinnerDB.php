<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Class to do database operations regarding the winner of the week.
 */

require_once __DIR__.'/../../config/GlobalConfig.php';

require_once __DIR__.'/../../helperClasses/database/Database.php';
require_once __DIR__.'/../../helperClasses/database/DatabaseException.php';

require_once 'WeeklyWinnerData.php';
require_once 'WeeklyWinnerDBException.php';

abstract class WeeklyWinnerDB {
    
    /**
     * Adds a weekly winner entry for the given user ID.
     * 
     * @param int $userId ID of user who wins
     * @throws Exception generic error for if something goes wrong while talking to the database
     * @throws WeeklyWinnerDBException error for if something goes wrong while creating the winner of the week
     */
    public static function addWeeklyWinner($userId) {
        try {
            $conn = Database::getConnection();
            
            $commString = 'INSERT INTO stippers_weekly_winners (start_of_week, user) VALUES (DATE(DATE_SUB(CONVERT_TZ(NOW(), @@global.time_zone, ?), INTERVAL WEEKDAY(CONVERT_TZ(NOW(), @@global.time_zone, ?)) DAY)), ?)';
            $stmt = $conn->prepare($commString);
            
            //Check if statement could be prepared
            if ($stmt) {
                
                $timezone = GlobalConfig::TIMEZONE;
                $stmt->bind_param('ssi', $timezone, $timezone, $userId);
                if (!$stmt->execute())
                    if ($stmt->errno == 1062)
                        throw new WeeklyWinnerDBException('There already is a winner this week.', WeeklyWinnerDBException::WEEKALREADYHASWINNER);
                    else
                        throw new WeeklyWinnerDBException('Unknown error during statement execution while adding weekly winner.', WeeklyWinnerDBException::UNKNOWNERROR);
            }
            else
               throw new WeeklyWinnerDBException('Cannot prepare statement.', WeeklyWinnerDBException::CANNOTPREPARESTMT);
        }
        catch (Exception $ex) {
            throw $ex;
        }
        finally {
            if (isset($conn)) {
                $conn->kill($conn->thread_id);
                $conn->close();
            }
        }
    }

    /**
     * Get last winners. The amount given is returned.
     * 
     * @param int $nWinners amount of winners to return
     * @return array last winners
     * @throws Exception generic error for if something goes wrong while talking to the database
     * @throws WeeklyWinnerDBException error for if something goes wrong while getting the last winners of the week
     */
    public static function getLastNWinners($nWinners) {
        try {
            $conn = Database::getConnection();
            
            $commString = 'SELECT user FROM stippers_weekly_winners ORDER BY start_of_week DESC LIMIT ?';
            $stmt = $conn->prepare($commString);
            
            //Check if statement could be prepared
            if ($stmt) {
                
                $stmt->bind_param('i', $nWinners);
                if (!$stmt->execute())
                    throw new WeeklyWinnerDBException('Unknown error during statement execution while getting last winners.', WeeklyWinnerDBException::UNKNOWNERROR);
                else {
                    $userIds = array();
                    $stmt->bind_result($userId);
                    while ($stmt->fetch())
                        array_push($userIds, $userId);
                    
                    return $userIds;
                }
            }
            else
               throw new WeeklyWinnerDBException('Cannot prepare statement.', WeeklyWinnerDBException::CANNOTPREPARESTMT);
        }
        catch (Exception $ex) {
            throw $ex;
        }
        finally {
            if (isset($conn)){
                $conn->kill($conn->thread_id);
                $conn->close();
            }
        }
    }
    
    /**
     * Get data of this week's winner.
     * 
     * @return WeeklyWinnerData object containing data of the winner of this week.
     * @throws Exception generic error for if something goes wrong while talking to the database
     * @throws WeeklyWinnerDBException error for if something goes wrong while getting the winner of this week
     */
    public static function getThisWeeksWinnerData() {
        try {
            $conn = Database::getConnection();
            
            $commString = 'SELECT DATE_FORMAT(start_of_week, "%d/%m/%Y"), user, has_collected_prize FROM stippers_weekly_winners WHERE start_of_week = (SELECT DATE(DATE_SUB(CONVERT_TZ(NOW(), @@global.time_zone, ?), INTERVAL WEEKDAY(CONVERT_TZ(NOW(), @@global.time_zone, ?)) DAY)))';
            $stmt = $conn->prepare($commString);
            
            //Check if statement could be prepared
            if ($stmt) {
                
                $timezone = GlobalConfig::TIMEZONE;
                $stmt->bind_param('ss', $timezone, $timezone);
                if (!$stmt->execute())
                    throw new WeeklyWinnerDBException('Unknown error during statement execution while getting winner', WeeklyWinnerDBException::UNKNOWNERROR);
                else {
                    $stmt->bind_result($startOfWeek, $userId, $hasCollectedPrize);
                    $result = $stmt->fetch();
                    if ($result)
                        return new WeeklyWinnerData($startOfWeek, $userId, $hasCollectedPrize);
                    else if ($result == null)
                        return null;
                    else
                        throw new WeeklyWinnerDBException('Unknown error during statement execution while getting winner.', WeeklyWinnerDBException::UNKNOWNERROR);
                }
            }
            else
               throw new WeeklyWinnerDBException('Cannot prepare statement.', WeeklyWinnerDBException::CANNOTPREPARESTMT);
        }
        catch (Exception $ex) {
            throw $ex;
        }
        finally {
            if (isset($conn)){
                $conn->kill($conn->thread_id);
                $conn->close();
            }
        }
    }
    
    /**
     * Updates data of the winner of this week.
     * 
     * @param type $oldWinnerData old data to compare with
     * @param type $newWinnerData object with updated data
     * @throws Exception generic error for if something goes wrong while talking to the database
     * @throws WeeklyWinnerDBException error for if something goes wrong while updating the winner of this week
     */
    public static function updateWeeklyWinnerData($oldWinnerData, $newWinnerData) {
        try {
            $conn = Database::getConnection();
            $commString = 'UPDATE stippers_weekly_winners SET has_collected_prize = ? WHERE start_of_week = STR_TO_DATE(?, "%d/%m/%Y") AND user = ? AND has_collected_prize = ?';
            $stmt = $conn->prepare($commString);
            
            //Check if statement could be prepared
            if ($stmt) {
                
                $stmt->bind_param('isii', $newWinnerData->hasCollectedPrize, $oldWinnerData->startOfWeek, $oldWinnerData->userId, $oldWinnerData->hasCollectedPrize);
                if (!$stmt->execute())
                    throw new WeeklyWinnerDBException('Unknown error during statement execution while updating winner.', WeeklyWinnerDBException::UNKNOWNERROR);
                else if ($stmt->affected_rows == 0) {
                    throw new WeeklyWinnerDBException('The winner is out of date, someone else has probably already changed the winner.', WeeklyWinnerDBException::WINNEROUTOFDATE);
                }
            }
            else
               throw new WeeklyWinnerDBException('Cannot prepare statement.', WeeklyWinnerDBException::CANNOTPREPARESTMT);
        }
        catch (Exception $ex) {
            throw $ex;
        }
        finally {
            if (isset($conn)){
                $conn->kill($conn->thread_id);
                $conn->close();
            }
        }
    }
}