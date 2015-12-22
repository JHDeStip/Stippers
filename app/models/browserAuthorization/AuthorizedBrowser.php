<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Model representing a browser with permissions.
 */

class AuthorizedBrowser {
    public $uuid;
    public $name;
    public $canAddUpdateUsers;
    public $canCheckIn;

    /**
     * 
     * @param string $uuid UUID of browser
     * @param string $name name given to browser
     * @param bool $canAddUpdateUsers permission for creating and updating users
     * @param type $canCheckIn permission for checking in
     */
    public function __construct($uuid, $name, $canAddUpdateUsers, $canCheckIn) {
        $this->uuid = $uuid;
        $this->name = $name;
        $this->canAddUpdateUsers = $canAddUpdateUsers;
        $this->canCheckIn = $canCheckIn;
    }
}