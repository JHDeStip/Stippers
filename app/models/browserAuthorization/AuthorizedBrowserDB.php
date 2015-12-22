<?php
/**
 * Created by PhpStorm.
 * User: Stan
 * Date: 3/12/2014
 * Time: 13:46
 */
require_once __DIR__.'/../../helperClasses/database/Database.php';
require_once 'AuthorizedBrowser.php';
require_once 'AuthorizedBrowserDBException.php';

abstract class AuthorizedBrowserDB {
    
    public static function getAuthorizedBrowsers() {
        $browsers = array();

        try {
            $conn = Database::getConnection();
            $commString = 'SELECT uuid, name, can_create_users, can_check_in FROM stippers_authorized_browsers';
            $stmt = $conn->prepare($commString);
            
            if (!$stmt->execute())
                throw new AuthorizedBrowserDBException('Unknown error during statement execution while getting authorized browsers.', AuthorizedBrowserDBException::UNKNOWNERROR);
            else {
                $stmt->bind_result($uuid, $name, $canAddUpdateUsers, $canCheckIn);
                $i = 0;
                
                while ($stmt->fetch()) {
                    if ($canAddUpdateUsers == 0)
                        $canAddUpdateUsersBool = false;
                    else
                        $canAddUpdateUsersBool = true;
                    if ($canCheckIn == 0)
                        $canCheckInBool = false;
                    else
                        $canCheckInBool = true;
                    
                    $browsers[$i] = new AuthorizedBrowser($uuid, $name, $canAddUpdateUsersBool, $canCheckInBool);
                    $i++;
                }

                return $browsers;
            }
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

    public static function getBasicAuthorizedBrowser($uuid){
        try {
            $conn = Database::getConnection();
            $commString = 'SELECT can_create_users, can_check_in FROM stippers_authorized_browsers WHERE uuid = ?';
            $stmt = $conn->prepare($commString);
            $stmt->bind_param('s', $uuid);
            
            if (!$stmt->execute())
                throw new AuthorizedBrowserDBException('Unknown error during statement execution while getting authorized browser.', AuthorizedBrowserDBException::UNKNOWNERROR);
            else {
                $stmt->bind_result($canAddUpdateUsers, $canCheckIn);
                $browser = new AuthorizedBrowser(null, null, false, false);
                
                if ($stmt->fetch()) {
                    if ($canAddUpdateUsers == 0)
                        $canAddUpdateUsersBool = false;
                    else
                        $canAddUpdateUsersBool = true;
                    
                    if ($canCheckIn == 0)
                        $canCheckInBool = false;
                    else
                        $canCheckInBool = true;
                    
                    $browser->canAddUpdateUsers = $canAddUpdateUsersBool;
                    $browser->canCheckIn = $canCheckInBool;
                }
                
                return $browser;
            }
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

    public static function addAuthorizedBrowser($browser) {
        try {
            $conn = Database::getConnection();
            $commString = 'INSERT INTO stippers_authorized_browsers (uuid, name, can_create_users, can_check_in) VALUES (?, ?, ?, ?)';
            $stmt = $conn->prepare($commString);
            $stmt->bind_param('ssii', $browser->uuid, $browser->name, $browser->canAddUpdateUsers, $browser->canCheckIn);
            
            if (!$stmt->execute()) {
                if ($stmt->errno == 1062)
                    throw new AuthorizedBrowserDBException('A browser with this name already exists.', AuthorizedBrowserDBException::BROWSERNAMEEXISTS);
                else
                    throw new AuthorizedBrowserDBException('Unknown error during statement execution while setting authorized browser.', AuthorizedBrowserDBException::UNKNOWNERROR);
            }
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

    public static function updateAuthorizedBrowser($oldBrowser, $newBrowser) {
        try {
            $conn = Database::getConnection();
            $commString = 'UPDATE stippers_authorized_browsers SET name = ?, can_create_users = ?, can_check_in = ? WHERE uuid = ? AND name = ? AND can_create_users = ? AND can_check_in = ?';
            $stmt = $conn->prepare($commString);
            $stmt->bind_param('siissii', $newBrowser->name, $newBrowser->canAddUpdateUsers, $newBrowser->canCheckIn, $oldBrowser->uuid, $oldBrowser->name, $oldBrowser->canAddUpdateUsers, $oldBrowser->canCheckIn);
            
            if (!$stmt->execute()) {
                if ($stmt->errno == 1062)
                    throw new AuthorizedBrowserDBException('A browser with this name already exists.', AuthorizedBrowserDBException::BROWSERNAMEEXISTS);
                else
                    throw new AuthorizedBrowserDBException('Unknown error during statement execution while updating authorized browser.', AuthorizedBrowserDBException::UNKNOWNERROR);
            }
            else
                if ($stmt->affected_rows == 0)
                    throw new AuthorizedBrowserDBException('Browser out of date.', AuthorizedBrowserDBException::BROWSEROUTOFDATE);
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

    public static function removeAuthorizedBrowser($browser) {
        try {
            $conn = Database::getConnection();
            $commString = 'DELETE FROM stippers_authorized_browsers WHERE uuid = ? AND name = ? AND can_create_users = ? AND can_check_in = ?';
            $stmt = $conn->prepare($commString);
            $stmt->bind_param('ssii', $browser->uuid, $browser->name, $browser->canAddUpdateUsers, $browser->canCheckIn);
            
            if (!$stmt->execute())
                throw new AuthorizedBrowserDBException('Unknown error during statement execution while removing authorized browser.', AuthorizedBrowserDBException::UNKNOWNERROR);
            else
                if ($stmt->affected_rows == 0)
                    throw new AuthorizedBrowserDBException('Browser out of date.', AuthorizedBrowserDBException::BROWSEROUTOFDATE);
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