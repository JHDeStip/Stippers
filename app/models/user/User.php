<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Model representing a user with all it's aspects that are not directly related to it's membership.
 */

class User {
    public $userId;
    public $email;
    public $firstName;
    public $lastName;
    public $passwordHash;
    public $balance;
    public $phone;
    public $dateOfBirth;
    public $street;
    public $houseNumber;
    public $city;
    public $postalCode;
    public $country;
    public $creationTime;
    public $isAdmin;
    public $isHintManager;
    public $isUserManager;
    public $isAuthorizedBrowserManager;

    /**
     * Gets the full name of the user.
     * 
     * @return string full name of user
     */
    public function getFullName() {
        return $this->firstName . ' ' . $this->lastName;
    }
}