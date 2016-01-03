<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Class to do database operations regarding browser models.
 */

require_once __DIR__.'/../../helperClasses/database/Database.php';
require_once 'Browser.php';
require_once 'BrowserDBException.php';

abstract class BrowserDB {
    
    /**
     * Gets all browsers.
     * 
     * @return array Browser
     * @throws Exception generic error for if something goes wrong while talking to the database
     * @throws BrowserDBException error for if something goes wrong while getting the browsers
     */
    public static function getBrowsers() {
        try {
            $conn = Database::getConnection();
            $commString = 'SELECT browser_id, uuid, name, can_add_renew_users, can_check_in, is_cash_register FROM stippers_browsers';
            $stmt = $conn->prepare($commString);
            
            //Check if statement could be prepared
            if ($stmt) {
                
                if (!$stmt->execute())
                    throw new BrowserDBException('Unknown error during statement execution while getting browsers.', BrowserDBException::UNKNOWNERROR);
                else {
                    $stmt->bind_result($browserId, $uuid, $name, $canAddRenewUsers, $canCheckIn, $isCashRegister);
                    $browsers = array();
                    
                    while ($stmt->fetch()) {
                        $canAddRenewUsersBool = ($canAddRenewUsers != 0);
                        $canCheckInBool = ($canCheckIn != 0);
                        $isCashRegister = ($isCashRegister != 0);
                        
                        array_push($browsers, new Browser($browserId, $uuid, $name, $canAddRenewUsersBool, $canCheckInBool, $isCashRegister));
                    }
                    
                    return $browsers;
                }
            }
            else
                throw new BrowserDBException('Cannot prepare statement.', BrowserDBException::CANNOTPREPARESTMT);
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
     * Gets the browser for the given UUID.
     * 
     * @param string $uuid UUID of browser to get
     * @return Browser browser with permissions
     * @throws Exception generic error for if something goes wrong while talking to the database
     * @throws BrowserDBException error for if something goes wrong while getting the browser
     */
    public static function getBasicBrowserByUuid($uuid){
        try {
            $conn = Database::getConnection();
            $commString = 'SELECT browser_id, can_add_renew_users, can_check_in, is_cash_register FROM stippers_browsers WHERE uuid = ?';
            $stmt = $conn->prepare($commString);
            
            //Check if statement could be prepared
            if ($stmt) {
                
                $stmt->bind_param('s', $uuid);
                
                if (!$stmt->execute())
                    throw new BrowserDBException('Unknown error during statement execution while getting browser.', BrowserDBException::UNKNOWNERROR);
                else {
                    $stmt->bind_result($browserId, $canAddRenewUsers, $canCheckIn, $isCashRegister);
                    
                    if ($stmt->fetch()) {
                        $canAddRenewUsers = ($canAddRenewUsers != 0);
                        $canCheckIn = ($canCheckIn != 0);
                        $isCashRegister = ($isCashRegister != 0);
                        return new Browser($browserId, null, null, $canAddRenewUsers, $canCheckIn, $isCashRegister);
                    }
                    else
                        throw new BrowserDBException('No browser was found for this id.', BrowserDBException::NOBROWSERFORUUID);
                    
                }
            }
            else
                throw new BrowserDBException('Cannot prepare statement.', BrowserDBException::CANNOTPREPARESTMT);
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
     * Gets the browser for the given ID.
     * 
     * @param long $id ID of browser to get
     * @return Browser browser with permissions
     * @throws Exception generic error for if something goes wrong while talking to the database
     * @throws BrowserDBException error for if something goes wrong while getting the browser
     */
    public static function getBrowserById($id){
        try {
            $conn = Database::getConnection();
            $commString = 'SELECT uuid, name, can_add_renew_users, can_check_in, is_cash_register FROM stippers_browsers WHERE browser_id = ?';
            $stmt = $conn->prepare($commString);
            
            //Check if statement could be prepared
            if ($stmt) {
                
                $stmt->bind_param('i', $id);
                
                if (!$stmt->execute())
                    throw new BrowserDBException('Unknown error during statement execution while getting browser.', BrowserDBException::UNKNOWNERROR);
                else {
                    $stmt->bind_result($uuid, $name, $canAddRenewUsers, $canCheckIn, $isCashRegister);
                    
                    if ($stmt->fetch()) {
                        $canAddRenewUsers = ($canAddRenewUsers != 0);
                        $canCheckIn = ($canCheckIn != 0);
                        $isCashRegister = ($isCashRegister != 0);
                        return new Browser($id, $uuid, $name, $canAddRenewUsers, $canCheckIn, $isCashRegister);
                    }
                    else
                        throw new BrowserDBException('No browser was found for this id.', BrowserDBException::NOBROWSERFORID);
                }
            }
            else
                throw new BrowserDBException('Cannot prepare statement.', BrowserDBException::CANNOTPREPARESTMT);
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
     * Adds a new browser.
     * 
     * @param Browser $browser browser to add to the database
     * @throws Exception generic error for if something goes wrong while talking to the database
     * @throws BrowserDBException error for if something goes wrong while adding the browser
     */
    public static function addBrowser($browser) {
        try {
            $conn = Database::getConnection();
            
            $commString = 'INSERT INTO stippers_browsers (uuid, name, can_add_renew_users, can_check_in, is_cash_register) VALUES (?, ?, ?, ?, ?)';
            $stmt = $conn->prepare($commString);
            
            //Check if statement could be prepared
            if ($stmt) {
                
                $stmt->bind_param('ssiii', $browser->uuid, $browser->name, $browser->canAddRenewUsers, $browser->canCheckIn, $browser->isCashRegister);
                
                if (!$stmt->execute()) {
                    if ($stmt->errno == 1062)
                        throw new BrowserDBException('A browser with this name already exists.', BrowserDBException::BROWSERNAMEEXISTS);
                    else
                        throw new BrowserDBException('Unknown error during statement execution while setting browser.', BrowserDBException::UNKNOWNERROR);
                }
            }
            else
                throw new BrowserDBException('Cannot prepare statement.', BrowserDBException::CANNOTPREPARESTMT);
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
     * Updates a browser.
     * 
     * @param Browser $oldBrowser browser with original data to check if someone else has already updated the browser
     * @param Browser $newBrowser browser with updated data
     * @throws Exception generic error for if something goes wrong while talking to the database
     * @throws BrowserDBException error for if something goes wrong while updating the browser
     */
    public static function updateBrowser($oldBrowser, $newBrowser) {
        try {
            $conn = Database::getConnection();
            $commString = 'UPDATE stippers_browsers SET name = ?, can_add_renew_users = ?, can_check_in = ?, is_cash_register = ? WHERE browser_id = ? AND uuid = ? AND name = ? AND can_add_renew_users = ? AND can_check_in = ? AND is_cash_register = ?';
            $stmt = $conn->prepare($commString);
            
            //Check if statement could be prepared
            if ($stmt) {
                
                $stmt->bind_param('siiiissiii', $newBrowser->name, $newBrowser->canAddRenewUsers, $newBrowser->canCheckIn, $newBrowser->isCashRegister, $oldBrowser->browserId, $oldBrowser->uuid, $oldBrowser->name, $oldBrowser->canAddRenewUsers, $oldBrowser->canCheckIn, $oldBrowser->isCashRegister);
    
                if (!$stmt->execute()) {
                    if ($stmt->errno == 1062)
                        throw new BrowserDBException('A browser with this name already exists.', BrowserDBException::BROWSERNAMEEXISTS);
                    else
                        throw new BrowserDBException('Unknown error during statement execution while updating browser.', BrowserDBException::UNKNOWNERROR);
                }
                else
                    if ($stmt->affected_rows == 0)
                        throw new BrowserDBException('Browser out of date.', BrowserDBException::BROWSEROUTOFDATE);
            }
            else
                throw new BrowserDBException('Cannot prepare statement.', BrowserDBException::CANNOTPREPARESTMT);
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
     * Removes a browser.
     * 
     * @param Browser $browser browser to remove
     * @throws Exception generic error for if something goes wrong while talking to the database
     * @throws BrowserDBException error for if something goes wrong while removing the browser
     */
    public static function removeBrowser($browser) {
        try {
            $conn = Database::getConnection();
            $commString = 'DELETE FROM stippers_browsers WHERE browser_id = ? AND uuid = ? AND name = ? AND can_add_renew_users = ? AND can_check_in = ? AND is_cash_register = ?';
            $stmt = $conn->prepare($commString);
            
            //Check if statement could be prepared
            if ($stmt) {
                
                $stmt->bind_param('issiii', $browser->browserId, $browser->uuid, $browser->name, $browser->canAddRenewUsers, $browser->canCheckIn, $browser->isCashRegister);
                
                if (!$stmt->execute())
                    throw new BrowserDBException('Unknown error during statement execution while removing browser.', BrowserDBException::UNKNOWNERROR);
                else
                    if ($stmt->affected_rows == 0)
                        throw new BrowserDBException('Browser out of date.', BrowserDBException::BROWSEROUTOFDATE);
            }
            else
                throw new BrowserDBException('Cannot prepare statement.', BrowserDBException::CANNOTPREPARESTMT);
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