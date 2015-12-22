<?php
/**
 * Created by PhpStorm.
 * User: Stan
 * Date: 27/07/14
 * Time: 13:31
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

    public function getFullName() {
        return $this->firstName . ' ' . $this->lastName;
    }
}