<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Model representing a browser with permissions.
 */

class Browser {
    public $browserId;
    public $uuid;
    public $name;
    public $canAddRenewUsers;
    public $canCheckIn;
    public $isCashRegister;

    /**
     * Constructor for browser with ID. 
     *
     * @param long $browser_id ID of browser
     * @param string $uuid UUID of browser
     * @param string $name name given to browser
     * @param bool $canAddRenewUsers permission for creating and updating users
     * @param bool $canCheckIn permission for checking in
     * @param bool $isCashRegister permission for managing cash on user accounts
     */
    public function __construct($browserId, $uuid, $name, $canAddRenewUsers, $canCheckIn, $isCashRegister) {
        $this->browserId = $browserId;
        $this->uuid = $uuid;
        $this->name = $name;
        $this->canAddRenewUsers = $canAddRenewUsers;
        $this->canCheckIn = $canCheckIn;
        $this->isCashRegister = $isCashRegister;
    }
}