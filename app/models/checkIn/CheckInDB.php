<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Class to do database operations regarding check-ins.
 */

require_once __DIR__.'/../../config/GlobalConfig.php';
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
            $minCheckInInterval = CheckInConfig::MIN_CHECK_IN_INTERVAL;
            
            //Check if statement could be prepared
            if ($stmt) {
                                
                $stmt->bind_param('iii', $userId, $minCheckInInterval, $userId);
                if (!$stmt->execute())
                    throw new CheckInDBException('Unknown error during statement execution while counting too soon check-ins.', CheckInDBException::UNKNOWNERROR);
                else {
                    $stmt->bind_result($nCheckIns);
                    if (!$stmt->fetch())
                        throw new CheckInDBException('Unknown error during statement execution while counting too soon check-ins.', CheckInDBException::UNKNOWNERROR);
                    else if($nCheckIns > 0)
                        //Return false because the user is already checked in
                        return false;
                    else {
                        $stmt->close();
                        $commString = 'INSERT INTO stippers_check_ins (user) VALUES (?)';
                        $stmt = $conn->prepare($commString);
                        
                        //Check if statement could be prepared
                        if ($stmt) {
                            $stmt->bind_param('i', $userId);
                            if (!$stmt->execute())
                                throw new CheckInDBException('Unknown error during statement execution while checking in.', CheckInDBException::UNKNOWNERROR);
                        }
                        else
                            throw new CheckInDBException('Cannot prepare statement.', CheckInDBException::CANNOTPREPARESTMT);
                        
                        //Return true because the check in was successful
                        return true;
                    }
                }
            }
            else
                throw new CheckInDBException('Cannot prepare statement.', CheckInDBException::CANNOTPREPARESTMT);
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
            
            //Check if statement could be prepared
            if ($stmt) {
                                    
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
            else
                throw new CheckInDBException('Cannot prepare statement.', CheckInDBException::CANNOTPREPARESTMT);
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
            
            //Check if statement could be prepared
            if ($stmt) {
                                
                $stmt->bind_param('i', $userId);
                if (!$stmt->execute())
                    throw new UserDBException('Unknown error during statement execution while counting the user\'s check-ins.', CheckInDBException::UNKNOWNERROR);
                else {
                    $stmt->bind_result($nCheckIns);
                    if ($stmt->fetch())
                        return $nCheckIns;
                    else
                        throw new UserDBException('Unknown error during statement execution while counting the user\'s check-ins.', CheckInDBException::UNKNOWNERROR);
                }
            }
            else
                throw new CheckInDBException('Cannot prepare statement.', CheckInDBException::CANNOTPREPARESTMT);
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

    public static function getMostCheckedInNonUserManagerUserIdBetween($fromTimeStamp, $toTimeStamp) : ?int
    {
        try {
            $conn = Database::getConnection();
            $commString = 'SELECT user_id, COUNT(user_id) AS occurance FROM stippers_check_ins JOIN stippers_users ON stippers_check_ins.user = stippers_users.user_id WHERE NOT user_id = ? AND is_user_manager = 0 AND time >= FROM_UNIXTIME(?) AND time < FROM_UNIXTIME(?) GROUP BY user ORDER BY occurance DESC LIMIT 1';
            $stmt = $conn->prepare($commString);
            
            //Check if statement could be prepared
            if ($stmt) {
                
                $adminId = GlobalConfig::ADMIN_ID;
                $stmt->bind_param('iii', $adminId, $fromTimeStamp, $toTimeStamp);
                if (!$stmt->execute())
                    throw new CheckInDBException('Unknown error during statement execution while getting most checked in user between 2 times.', CheckInDBException::UNKNOWNERROR);
                else {
                    $stmt->bind_result($userId, $occurance);
                    
                    if ($stmt->fetch()) {
                        return $userId;
                    }

                    return null;
                }
            }
            else
                throw new CheckInDBException('Cannot prepare statement.', MoneyTransactionDBException::CANNOTPREPARESTMT);
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
}