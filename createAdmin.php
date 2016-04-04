<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Create admin page. This file can be used to create the admin account right after the data base is set up.
 * This file should be removed once this is done.
 */

require_once 'app/config/SecurityConfig.php';

require_once 'app/helperClasses/database/DataBase.php';
require_once 'app/helperClasses/database/DataBaseException.php';

require_once 'app/models/user/User.php';

require_once 'app/helperClasses/random/Random.php';

// Generate password salt
$passwordSalt = Random::getGuid();

// Create new user
$user = new User();
$user->userId = 0;
$user->email = 'admin@stippers.be';
$user->firstName = 'admin';
$user->lastName = 'admin';
$user->street = 'Stippers street';
$user->houseNumber = '1';
$user->city = 'Stippers city';
$user->postalCode = '0000';
$user->country = 'Belgium';
$user->dateOfBirth = '23/03/1993';
$user->isAdmin = true;
$user->phone = '';
$user->passwordHash = hash_pbkdf2('sha256', 'Passw0rd', $passwordSalt, SecurityConfig::N_PASSWORD_HASH_ITERATIONS);

// Insert user in data base
$conn = DataBase::getConnection();
$commString = 'INSERT INTO stippers_users (user_id, email, first_name, last_name, password_hash, password_salt, phone, date_of_birth, street, house_number, city, postal_code, country) ' .
                    'VALUES (?, ?, ?, ?, ?, ?, ?, STR_TO_DATE(?, "%d/%m/%Y"), ?, ?, ?, ?, ?)';
$stmt = $conn->prepare($commString);
$stmt->bind_param('issssssssssss', $user->userId, $user->email, $user->firstName, $user->lastName, $user->passwordHash, $passwordSalt, $user->phone, $user->dateOfBirth, $user->street, $user->houseNumber, $user->city, $user->postalCode, $user->country);
                    
$stmt->execute();
$conn->close();