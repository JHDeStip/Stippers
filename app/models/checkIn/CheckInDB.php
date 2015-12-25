<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Class to do database operations regarding check-ins.
 */

require_once __DIR__.'/../../config/CheckInConfig.php';
require_once __DIR__.'/../../config/CheckInConfig.php';

require_once __DIR__.'/../../helperClasses/database/Database.php';
require_once 'CheckInDBException.php';

abstract class CheckInDB {
    
    /**
     * Checks-in a user who's ID is given.
     * 
     * @param int $userId ID of the user to check-in
     * @throws Exception generic error for if something goes wrong while talking to the database
     * @throws CheckInDBException error for if something goes wrong while checking the user in
     */
    public static function checkIn($userId){
        $locked = false;
        try {
            $conn = Database::getConnection();
/*
            if (!$conn->query("LOCK TABLES stippers_check_ins WRITE"))
                throw new CheckInDBException("Unknown error during statement execution while acquiring table lock.", CheckInDBException::UNKNOWNERROR);
            $locked = true;
            */
            $commString = 'SELECT COUNT(*) FROM stippers_check_ins WHERE user = ? AND (NOW() - INTERVAL ? HOUR < ANY (SELECT time FROM stippers_check_ins WHERE user = ?))';
            $stmt = $conn->prepare($commString);
            $minCheckInInterval = CheckInConfig::MINCHECKININTERVAL;
            $stmt->bind_param('iii', $userId, $minCheckInInterval, $userId);
            if (!$stmt->execute())
                throw new CheckInDBException('Unknown error during statement execution while counting too soon check-ins.', CheckInDBException::UNKNOWNERROR);
            else {
                $stmt->bind_result($nCheckIns);
                if (!$stmt->fetch())
                    throw new CheckInDBException('Unknown error during statement execution while counting too soon check-ins.', CheckInDBException::UNKNOWNERROR);
                else if($nCheckIns > 0)
                    throw new CheckInDBException('This user was checked in less than CheckInConfig::MINCHECKININTERVAL hours ago.', CheckInDBException::ALREADYCHECKEDIN);
                else {
                    $stmt->close();
                    $commString = 'INSERT INTO stippers_check_ins (user) VALUES (?)';
                    $stmt = $conn->prepare($commString);
                    $stmt->bind_param('i', $userId);
                    if (!$stmt->execute())
                        throw new CheckInDBException('Unknown error during statement execution while checking in.', CheckInDBException::UNKNOWNERROR);
                }
            }
        }
        catch (Exception $ex)
        {
            throw $ex;
        }
        finally {
            if (isset($conn)){
                /*
                if (locked) {
                    $commString = "UNLOCK TABLES";
                    $stmt = $conn->prepare($commString);
                    $stmt->execute();
                }*/
                $conn->kill($conn->thread_id);
                $conn->close();
            }
        }
    }

    /**
     * Get the number of check-ins this year for a user who's ID is given.
     * 
     * @param int $userId ID of user to get number of check-ins for
     * @return int number of check-ins
     * @throws Exception generic error for if something goes wrong while talking to the database
     * @throws CheckInDBException error for if something goes wrong while getting the number of check-ins
     */
    public static function getNCheckInsThisYearByUserId($userId){
        try {
            $conn = Database::getConnection();
            $commString = 'SELECT count(*) FROM stippers_check_ins WHERE user = ? AND YEAR(time) = (SELECT YEAR(NOW()))';
            $stmt = $conn->prepare($commString);
            $stmt->bind_param('i', $userId);
            if (!$stmt->execute())
                throw new UserDBException('Unknown error during statement execution while getting the user\'s check-ins.', CheckInDBException::UNKNOWNERROR);
            else {
                $stmt->bind_result($nCheckIns);
                if ($stmt->fetch())
                    return $nCheckIns;
                else
                    throw new UserDBException('Unknown error during statement execution while getting the user\'s check-ins.', CheckInDBException::UNKNOWNERROR);
            }
        }
        catch (Exception $ex) {
            throw $ex;
        }
        finally
        {
            if (isset($conn)){
                $conn->kill($conn->thread_id);
                $conn->close();
            }
        }
    }

    /**
     * Get the total number of check-ins for a user who's ID is given.
     * 
     * @param int $userId ID of user to get number of check-ins for
     * @return int number of check-ins
     * @throws Exception generic error for if something goes wrong while talking to the database
     * @throws CheckInDBException error for if something goes wrong while getting the number of check-ins
     */
    public static function getTotalCheckInsByUserId($userId){
        try {
            $conn = Database::getConnection();
            $commString = 'SELECT count(*) FROM stippers_check_ins WHERE user = ?';
            $stmt = $conn->prepare($commString);
            $stmt->bind_param('i', $userId);
            if (!$stmt->execute())
                throw new UserDBException('Unknown error during statement execution while getting the user\'s check-ins.', CheckInDBException::UNKNOWNERROR);
            else {
                $stmt->bind_result($nCheckIns);
                if ($stmt->fetch())
                    return $nCheckIns;
                else
                    throw new UserDBException('Unknown error during statement execution while getting the user\'s check-ins.', CheckInDBException::UNKNOWNERROR);
            }
        }
        catch (Exception $ex) {
            throw $ex;
        }
        finally
        {
            if (isset($conn)){
                $conn->kill($conn->thread_id);
                $conn->close();
            }
        }
    }
}