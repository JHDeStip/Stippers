<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Class to do database operations regarding memberships.
 */

require_once __DIR__.'/../../config/GlobalConfig.php';
require_once __DIR__.'/../../helperClasses/database/Database.php';
require_once __DIR__.'/../../helperClasses/database/DatabaseException.php';
require_once 'MembershipDBException.php';

abstract class MembershipDB {
    
    /**
     * Checks if a user who's ID is given is a member this year.
     * 
     * @param int $userId ID of the user to check membership for
     * @return boolean if the user is a member this year.
     * @throws Exception generic error for if something goes wrong while talking to the database
     * @throws MembershipDBException error for if something goes wrong while checking the user's membership
     */
    public static function isUserMemberThisYearByUserId($userId) {
        try {
            $conn = Database::getConnection();
            $commString = 'SELECT count(*) FROM stippers_user_card_year WHERE user = ? AND membership_year = (SELECT YEAR(NOW()))';
            $stmt = $conn->prepare($commString);
            
            //Check if statement could be prepared
            if ($stmt) {
                                    
                $stmt->bind_param('i', $userId);
                
                if (!$stmt->execute()) {
                    $stmt->bind_result($userCardYear);
                    
                    if ($stmt->fetch()) {
                        if ($userCardYear == 0)
                            return false;
                        return true;
                    }
                    else
                        throw new MembershipDBException('Unknown error during statement execution while checking if the user is a member this year.', MembershipDBException::UNKNOWNERROR);
                }
            }
            else
                throw new MembershipDBException('Cannot prepare statement.', MembershipDBException::CANNOTPREPARESTMT);
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
     * Gets card number and number of check-ins per year for a given user ID.
     * 
     * @param int $userId ID of user to get details for
     * @return array array of details per year
     * @throws Exception generic error for if something goes wrong while talking to the database
     * @throws MembershipDBException error for if something goes wrong while checking the user's membership details
     */
    public static function getUserMembershipDetailsByUserId($userId) {
        try {
            $conn = Database::getConnection();
            $commString = 'SELECT membership_year, card, (SELECT count(*) FROM stippers_check_ins WHERE YEAR(time) = membership_year AND user = ?)
            FROM stippers_user_card_year WHERE user = ? ORDER BY membership_year DESC';
            $stmt = $conn->prepare($commString);
            
            //Check if statement could be prepared
            if ($stmt) {
                          
                $stmt->bind_param('ii', $userId, $userId);
                
                if (!$stmt->execute())
                    throw new MembershipDBException('Unknown error during statement execution while getting the user\'s membership details.', MembershipDBException::UNKNOWNERROR);
                else {
                    $stmt->bind_result($membershipYear, $cardNumber, $nCheckIns);
    
                    $userYearsDetails = array();
                    
                    while ($stmt->fetch())
                        array_push($userYearsDetails, ['membershipYear' => $membershipYear, 'cardNumber' => $cardNumber, 'nCheckIns' => $nCheckIns]);
    
                    return $userYearsDetails;
                }
            }
            else
                throw new MembershipDBException('Cannot prepare statement.', MembershipDBException::CANNOTPREPARESTMT);
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
     * Gets the card number for a given user id for the year we are in.
     * 
     * @param int $userId id of the user to get card number for
     * @return int card number
     */
    public static function getCardNumberByUserId($userId) {
        try {
            $conn = Database::getConnection();
            $commString = 'SELECT card FROM stippers_user_card_year WHERE user = @user AND year = YEAR(NOW())';
            $stmt = $conn->prepare($commString);
            
            //Check if statement could be prepared
            if ($stmt) {
                
                $stmt->bind_param('i', $userId);
                
                if (!$stmt->execute())
                    throw new MembershipDBException('Unknown error during statement execution while getting the card number.', MembershipDBException::UNKNOWNERROR);
                else {
                    $stmt->bind_result($cardNumber);
    
                    if ($stmt->fetch())
                        return $cardNumber;
                    else
                        throw new MembershipDBException('This user is not a member this year.', MembershipDBException::NOTCARDTHISYEAR);
                }
            }
            else
                throw new MembershipDBException('Cannot prepare statement.', MembershipDBException::CANNOTPREPARESTMT);
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
     * Get the IDs of all uers that are a member this year.
     * 
     * @return array IDs of users
     * @throws Exception generic error for if something goes wrong while talking to the database
     * @throws MembershipDBException error for if something goes wrong while getting the user IDs
     */
    public static function getUserIdsThisYear() {
        try {
            $conn = Database::getConnection();
            $commString = 'SELECT user FROM stippers_user_card_year WHERE membership_year = (SELECT YEAR(NOW()))';
            $stmt = $conn->prepare($commString);
            
            //Check if statement could be prepared
            if ($stmt) {
                       
                if (!$stmt->execute())
                    throw new MembershipDBException('Unknown error during statement execution while getting the user IDs.', MembershipDBException::UNKNOWNERROR);
                else {
                    $stmt->bind_result($userId);
    
                    $userIds = array();
                    
                    while ($stmt->fetch())
                        array_push($userIds, $userId);
    
                    return $userIds;
                }
            }
            else
                throw new MembershipDBException('Cannot prepare statement.', MembershipDBException::CANNOTPREPARESTMT);
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