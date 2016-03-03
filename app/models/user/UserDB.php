<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Class to do database operations regarding user models.
 */

require_once __DIR__.'/../../config/GlobalConfig.php';
require_once __DIR__.'/../../helperClasses/database/Database.php';
require_once __DIR__.'/../../helperClasses/database/DatabaseException.php';
require_once 'User.php';
require_once 'UserDBException.php';

abstract class UserDB {
    
    /**
     * Gets all basic users. A basic user is a user that has only
     * basic properties such as permissions set.
     * 
     * @return array array of users
     * @throws Exception generic error for if something goes wrong while talking to the database
     * @throws UserDBException error for if something goes wrong while getting the users
     */
    public static function getAllBasicUsers() {
        try {
            $conn = Database::getConnection();
            $commString = 'SELECT email, first_name, last_name, password_hash, is_admin, is_hint_manager, is_user_manager, is_browser_manager, is_money_manager ' .
                'FROM stippers_users';
            $stmt = $conn->prepare($commString);

            //Check if statement could be prepared
            if ($stmt) {
                           
                if (!$stmt->execute())
                    throw new UserDBException('Unknown error during statement execution while getting the user.', UserDBException::UNKNOWNERROR);
                else {
                    $users = array();
                    
                    $stmt->bind_result($email, $firstName, $lastName, $passwordHash, $isAdmin, $isHintManager, $isUserManager, $isBrowserManager, $isMoneyManager);
                    
                    while ($stmt->fetch()) {
                        $user = new User();
                        $user->email = $email;
                        $user->firstName = $firstName;
                        $user->lastName = $lastName;
                        $user->passwordHash = $passwordHash;
                        $user->isAdmin = $isAdmin;
                        $user->isHintManager = $isHintManager;
                        $user->isUserManager = $isUserManager;
                        $user->isBrowserManager = $isBrowserManager;
                        $user->isMoneyManager = $isMoneyManager;
                        
                        array_push($users, user);
                    }
                    
                    return $users;
                }
            }
            else
                throw new UserDBException('Cannot prepare statement.', UserDBException::CANNOTPREPARESTMT);
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
     * Gets a full user by it's ID. A full user is a user with all
     * properties set.
     * 
     * @param int $userId ID of the user to get
     * @return User user to get
     * @throws Exception generic error for if something goes wrong while talking to the database
     * @throws UserDBException error for if something goes wrong while getting the user
     */
    public static function getFullUserById($userId) {
        try {
            $conn = Database::getConnection();
            $commString = 'SELECT email, first_name, last_name, password_hash, balance, phone, DATE_FORMAT(date_of_birth, "%d/%m/%Y") date_of_birth, street, house_number, city, postal_code, country, is_admin, is_hint_manager, is_user_manager, is_browser_manager, is_money_manager, DATE_FORMAT(CONVERT_TZ(creation_time, @@global.time_zone, ?), "%d/%m/%Y %H:%i") creation_time ' .
                'FROM stippers_users WHERE user_id = ?';
            $stmt = $conn->prepare($commString);
            
            //Check if statement could be prepared
            if ($stmt) {
                
                $timezone = GlobalConfig::MYSQL_TIME_ZONE;
                $stmt->bind_param('si', $timezone, $userId);
    
                if (!$stmt->execute())
                    throw new UserDBException('Unknown error during statement execution while getting the user.', UserDBException::UNKNOWNERROR);
                else {
                    $stmt->bind_result($email, $firstName, $lastName, $passwordHash, $balance, $phone, $dateOfBirth, $street, $houseNumber, $city, $postalCode, $country, $isAdmin, $isHintManager, $isUserManager, $isBrowserManager, $isMoneyManager, $creationTime);
                    
                    if ($stmt->fetch()) {
                        $user = new User();
                        $user->userId = $userId;
                        $user->email = $email;
                        $user->firstName = $firstName;
                        $user->lastName = $lastName;
                        $user->passwordHash = $passwordHash;
                        $user->balance = $balance;
                        $user->phone = $phone;
                        $user->dateOfBirth = $dateOfBirth;
                        $user->street = $street;
                        $user->houseNumber = $houseNumber;
                        $user->city = $city;
                        $user->postalCode = $postalCode;
                        $user->country = $country;
                        $user->isAdmin = $isAdmin;
                        $user->isHintManager = $isHintManager;
                        $user->isUserManager = $isUserManager;
                        $user->isBrowserManager = $isBrowserManager;
                        $user->isMoneyManager = $isMoneyManager;
                        $user->creationTime = $creationTime;
                        return $user;
                    }
                    else
                        throw new UserDBException('No user was found for this id.', UserDBException::NOUSERFORID);
                }
            }
            else
                throw new UserDBException('Cannot prepare statement.', UserDBException::CANNOTPREPARESTMT);
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
     * Gets a basic user by it's ID. A basic user is a user that has only
     * basic properties such as permissions set.
     * 
     * @param int $userId ID of the user to get
     * @return User user to get
     * @throws Exception generic error for if something goes wrong while talking to the database
     * @throws UserDBException error for if something goes wrong while getting the user
     */
    public static function getBasicUserById($userId) {
        try {
            $conn = Database::getConnection();
            $commString = 'SELECT email, first_name, last_name, password_hash, is_admin, is_hint_manager, is_user_manager, is_browser_manager, is_money_manager ' .
                'FROM stippers_users WHERE user_id = ?';
            $stmt = $conn->prepare($commString);
            
            //Check if statement could be prepared
            if ($stmt) {
                
                $stmt->bind_param('i', $userId);
    
                if (!$stmt->execute())
                    throw new UserDBException('Unknown error during statement execution while getting the user.', UserDBException::UNKNOWNERROR);
                else {
                    $stmt->bind_result($email, $firstName, $lastName, $passwordHash, $isAdmin, $isHintManager, $isUserManager, $isBrowserManager, $isMoneyManager);
                    
                    if ($stmt->fetch()) {
                        $user = new User();
                        $user->userId = $userId;
                        $user->email = $email;
                        $user->firstName = $firstName;
                        $user->lastName = $lastName;
                        $user->passwordHash = $passwordHash;
                        $user->isAdmin = $isAdmin;
                        $user->isHintManager = $isHintManager;
                        $user->isUserManager = $isUserManager;
                        $user->isBrowserManager = $isBrowserManager;
                        $user->isMoneyManager = $isMoneyManager;
                        return $user;
                    }
                    else
                        throw new UserDBException('No user was found for this id.', UserDBException::NOUSERFORID);
                }
            }
            else
                throw new UserDBException('Cannot prepare statement.', UserDBException::CANNOTPREPARESTMT);
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
     * Gets a basic user by it's ID. A basic user is a user that has only
     * basic properties such as permissions set.
     * Auth means that only users that are a member this year and the admin are returned.
     *
     * @param int $userId ID of the user to get
     * @return User user to get
     * @throws Exception generic error for if something goes wrong while talking to the database
     * @throws UserDBException error for if something goes wrong while getting the user
     */
    public static function getAuthUserById($userId) {
        try {
            $conn = Database::getConnection();
            $commString = 'SELECT email, first_name, last_name, password_hash, is_admin, is_hint_manager, is_user_manager, is_browser_manager, is_money_manager '
                .'FROM stippers_users LEFT JOIN stippers_user_card_year ON user_id = user '
                .'WHERE user_id = ? AND (membership_year = YEAR(CONVERT_TZ(NOW(), @@global.time_zone, ?)) OR user_id = ?)';
            $stmt = $conn->prepare($commString);
            
            //Check if statement could be prepared
            if ($stmt) {
                
                $timezone = GlobalConfig::MYSQL_TIME_ZONE;
                $adminId = GlobalConfig::ADMIN_ID;
                $stmt->bind_param('isi', $userId, $timezone, $adminId);
    
                if (!$stmt->execute())
                    throw new UserDBException('Unknown error during statement execution while getting the user.', UserDBException::UNKNOWNERROR);
                else {
                    $stmt->bind_result($email, $firstName, $lastName, $passwordHash, $isAdmin, $isHintManager, $isUserManager, $isBrowserManager, $isMoneyManager);
                    
                    if ($stmt->fetch()) {
                        $user = new User();
                        $user->userId = $userId;
                        $user->email = $email;
                        $user->firstName = $firstName;
                        $user->lastName = $lastName;
                        $user->passwordHash = $passwordHash;
                        $user->isAdmin = $isAdmin;
                        $user->isHintManager = $isHintManager;
                        $user->isUserManager = $isUserManager;
                        $user->isBrowserManager = $isBrowserManager;
                        $user->isMoneyManager = $isMoneyManager;
                        return $user;
                    }
                    else
                        throw new UserDBException('No user was found for this id.', UserDBException::NOUSERFORID);
                }
            }
            else
                throw new UserDBException('Cannot prepare statement.', UserDBException::CANNOTPREPARESTMT);
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
     * Gets a user's password salt by it's email.
     * 
     * @param string $email email of the user to get the salt for
     * @return string salt of the user
     * @throws Exception generic error for if something goes wrong while talking to the database
     * @throws UserDBException error for if something goes wrong while getting the salt
     */
    public static function getPasswordSaltByEmail($email) {
        try {
            $conn = Database::getConnection();
            $commString = 'SELECT password_salt FROM stippers_users WHERE email = ?';
            $stmt = $conn->prepare($commString);
            
            //Check if statement could be prepared
            if ($stmt) {
                    
                $stmt->bind_param('s', $email);
                
                if (!$stmt->execute())
                    throw new UserDBException('Unknown error during statement execution while getting the user\'s password salt.', UserDBException::UNKNOWNERROR);
                else {
                    $stmt->bind_result($passwordSalt);
                    
                    if ($stmt->fetch())
                        return $passwordSalt;
                    else
                        throw new UserDBException('No user was found for this is email.', UserDBException::NOUSERFOREMAIL);
                }
            }
            else
                throw new UserDBException('Cannot prepare statement.', UserDBException::CANNOTPREPARESTMT);
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
     * Gets a user's password salt by it's ID.
     * 
     * @param int $userId ID of the user to get the salt for
     * @return string salt of the user
     * @throws Exception generic error for if something goes wrong while talking to the database
     * @throws UserDBException error for if something goes wrong while getting the salt
     */
    public static function getPasswordSaltByUserId($userId) {
        try {
            $conn = Database::getConnection();
            $commString = 'SELECT password_salt FROM stippers_users WHERE user_id = ?';
            $stmt = $conn->prepare($commString);
            
            //Check if statement could be prepared
            if ($stmt) {
                
                $stmt->bind_param('i', $userId);
                
                if (!$stmt->execute())
                    throw new UserDBException('Unknown error during statement execution while getting the user\'s password salt.', UserDBException::UNKNOWNERROR);
                else {
                    $stmt->bind_result($passwordSalt);
                    
                    if ($stmt->fetch())
                        return $passwordSalt;
                    else
                        throw new UserDBException('No user was found for this is user id.', UserDBException::NOUSERFORID);
                }
            }
            else
                throw new UserDBException('Cannot prepare statement.', UserDBException::CANNOTPREPARESTMT);
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
     * Get all users that match the specified search criteria.
     * Only specified properties are included
     * 
     * @param array $select properties to include
     * @param array $search values to match
     * @param array $options extra options
     * @return array array with users matching the criteria
     * @throws Exception generic error for if something goes wrong while talking to the database
     * @throws UserDBException error for if something goes wrong while getting the users
     */
    public static function getSearchUsers($select, $search, $options) {
        try {
            //We have 4 query parts: select, order by, group by and search.
            $selectPart = 'user_id';
            $orderPart = ' ORDER BY null';
            $groupByPart = ' GROUP BY user_id';
            $params['types'] = '';
            $searchPart = 'email LIKE ? AND first_name LIKE ? AND last_name LIKE ? AND balance LIKE ? AND phone LIKE ? AND date_of_birth LIKE ? AND street LIKE ? AND house_number LIKE ? AND city LIKE ? AND postal_code LIKE ? AND country LIKE ? AND card LIKE ? AND stippers_user_card_year.membership_year LIKE ? ';

            //Add small parst to the parts for the specified options properties and properties to include
            if (isset($options['orderByBirthday']) && $options['orderByBirthday']) {
                $selectPart .= ', DATE_FORMAT(date_of_birth, "%m/%d") birthday';
                $orderPart .= ', birthday ASC';
                $groupByPart .= ', birthday';
            }
            if (isset($select['lastName']) && $select['lastName']) {
                $selectPart .= ', last_name';
                $orderPart .= ', last_name';
                $groupByPart .= ', last_name';
            }
            if (isset($select['firstName']) && $select['firstName']) {
                $selectPart .= ', first_name';
                $orderPart .= ', first_name';
                $groupByPart .= ', first_name';
            }
            if (isset($select['membershipYear']) && $select['membershipYear']) {
                $selectPart .= ', stippers_user_card_year.membership_year';
                $orderPart .= ', membership_year DESC';
                $groupByPart .= ', membership_year';
            }
            if (isset($select['street']) && $select['street']) {
                $selectPart .= ', street';
                $orderPart .= ', street';
                $groupByPart .= ', street';
            }
            if (isset($select['houseNumber']) && $select['houseNumber']) {
                $selectPart .= ', house_number';
                $orderPart .= ', house_number';
                $groupByPart .= ', house_number';
            }
            if (isset($select['city']) && $select['city']) {
                $selectPart .= ', city';
                $orderPart .= ', city';
                $groupByPart .= ', city';
            }
            if (isset($select['postalCode']) && $select['postalCode']) {
                $selectPart .= ', postal_code';
                $orderPart .= ', postal_code';
                $groupByPart .= ', postal_code';
            }
            if (isset($select['country']) && $select['country']) {
                $selectPart .= ', country';
                $orderPart .= ', country';
                $groupByPart .= ', country';
            }
            if (isset($select['email']) && $select['email']) {
                $selectPart .= ', email';
                $orderPart .= ', email';
                $groupByPart .= ', email';
            }
            if (isset($select['balance']) && $select['balance']) {
                $selectPart .= ', balance';
                $groupByPart .= ', balance';
            }
            if (isset($select['phone']) && $select['phone']) {
                $selectPart .= ', phone';
                $groupByPart .= ', phone';
            }
            if (isset($select['dateOfBirth']) && $select['dateOfBirth']) {
                $selectPart .= ', DATE_FORMAT(date_of_birth, "%d/%m/%Y") date_of_birth';
                $groupByPart .= ', date_of_birth';
            }
            if (isset($select['isAdmin']) && $select['isAdmin']) {
                $selectPart .= ', is_admin';
                $groupByPart .= ', is_admin';
            }
            /*
            This is for a future feature later
            if ($select['isHintManager']) {
                $selectPart .= ', is_hint_manager';
                $groupByPart .= ', is_hint_manager';
            }
            */
            if (isset($select['isUserManager']) && $select['isUserManager']) {
                $selectPart .= ', is_user_manager';
                $groupByPart .= ', is_user_manager';
            }
            if (isset($select['isBrowserManager']) && $select['isBrowserManager']) {
                $selectPart .= ', is_browser_manager';
                $groupByPart .= ', is_browser_manager';
            }
            if (isset($select['isMoneyManager']) && $select['isMoneyManager']) {
                $selectPart .= ', is_money_manager';
                $groupByPart .= ', is_money_manager';
            }
            if (isset($select['creationTime']) && $select['creationTime']) {
                $selectPart .= ', DATE_FORMAT(CONVERT_TZ(creation_time, @@global.time_zone, ?), "%d/%m/%Y %H:%i") creation_time';
                $params['types'] .= 's';
                $timezone = GlobalConfig::MYSQL_TIME_ZONE;
                $params['timezone'] = &$timezone;
                $groupByPart .= ', creation_time';
            }
            if (isset($select['nCheckInsPerYear']) && $select['nCheckInsPerYear']) {
                $selectPart .= ', COUNT(*) n_check_ins';
                //Don't group by this! This is what we need 'per group'!
            }
            if (isset($select['cardNumber']) && $select['cardNumber']) {
                $selectPart .= ', card';
                $groupByPart .= ', card';
            }
            //Create the parameter list to pass to the query statement
            $params['types'] .= 'ssssssssssssss';
            
            //Set timezone for JOIN with check in
            $timezone = GlobalConfig::MYSQL_TIME_ZONE;
            $params['timezoneCheckInTime'] = &$timezone;
            
            $likeStringEmail = (isset($search['email']) ? '%'.$search['email'].'%' : '%');
            $params['email'] = &$likeStringEmail;
            $likeStringFirstName = (isset($search['firstName']) ? '%'.$search['firstName'].'%' : '%');
            $params['firstName'] = &$likeStringFirstName;
            $likeStringLastName = (isset($search['lastName']) ? '%'.$search['lastName'].'%' : '%');
            $params['lastName'] = &$likeStringLastName;
            $likeStringBalance = (isset($search['balance']) ? '%'.$search['balance'].'%' : '%');
            $params['balance'] = &$likeStringBalance;
            $likeStringPhone = (isset($search['phone']) ? '%'.$search['phone'].'%' : '%');
            $params['phone'] = &$likeStringPhone;
            $likeStringDateOfBirth = (isset($search['dateOfBirth']) ? '%'.$search['dateOfBirth'].'%' : '%');
            $params['dateofBirth'] = &$likeStringDateOfBirth;
            $likeStringStreet = (isset($search['street']) ? '%'.$search['street'].'%' : '%');
            $params['street'] = &$likeStringStreet;
            $likeStringHouseNumber = (isset($search['houseNumber']) ? '%'.$search['houseNumber'].'%' : '%');
            $params['houseNumber'] = &$likeStringHouseNumber;
            $likeStringCity = (isset($search['city']) ? '%'.$search['city'].'%' : '%');
            $params['city'] = &$likeStringCity;
            $likeStringPostalCode = (isset($search['postalCode']) ? '%'.$search['postalCode'].'%' : '%');
            $params['postalCode'] = &$likeStringPostalCode;
            $likeStringCountry = (isset($search['country']) ? '%'.$search['country'].'%' : '%');
            $params['country'] = &$likeStringCountry;
            $likeStringCardNumber = (isset($search['cardNumber']) ? '%'.$search['cardNumber'].'%' : '%');
            $params['cardNumber'] = &$likeStringCardNumber;
            $likeStringMembershipYear = (isset($search['membershipYear']) ? '%'.$search['membershipYear'].'%' : '%');
            $params['membershipYear'] = &$likeStringMembershipYear;

            if (isset($search['isAdmin']) && $search['isAdmin'] != '') {
                $params['types'] .= 'i';
                $searchPart .= ' AND is_admin = ?';
                $likeStringIsAdmin = $search['isAdmin'];
                $params['isAdmin'] = &$likeStringIsAdmin;
            }
            /*
            This is for a future feature.
            if ($search['isHintManager'] != '') {
                $params['types'] .= 'i';
                $searchPart .= ' AND is_hint_manager = ?';
                $likeStringIsHintManager = $search['isHintManager'];
                $params['isHintManager'] = &$likeStringIsHintManager;
            }
            */
            if (isset($search['isUserManager']) && $search['isUserManager'] != '') {
                $params['types'] .= 'i';
                $searchPart .= ' AND is_user_manager = ?';
                $likeStringIsUserManager = $search['isUserManager'];
                $params['isUserManager'] = &$likeStringIsUserManager;
            }
            if (isset($search['isBrowserManager']) && $search['isBrowserManager'] != '') {
                $params['types'] .= 'i';
                $searchPart .= ' AND is_browser_manager = ?';
                $likeStringIsBrowserManager = $search['isBrowserManager'];
                $params['isBrowserManager'] = &$likeStringIsBrowserManager;
            }
            if (isset($search['isMoneyManager']) && $search['isMoneyManager'] != '') {
                $params['types'] .= 'i';
                $searchPart .= ' AND is_money_manager = ?';
                $likeStringIsMoneyManager = $search['isMoneyManager'];
                $params['isMoneyManager'] = &$likeStringIsMoneyManager;
            }
            if (isset($search['onlyLastYear']) && $search['onlyLastYear']) {
                $searchPart .= ' AND stippers_user_card_year.membership_year >= ANY (SELECT membership_year FROM stippers_user_card_year WHERE user_id = user)';
            }

            //Here we're going to execute the query.
            $conn = Database::getConnection();
            //We paste the parts together.
            $commString = 'SELECT ' . $selectPart . ' FROM stippers_users INNER JOIN stippers_user_card_year ON stippers_user_card_year.user = user_id LEFT JOIN stippers_check_ins ON stippers_check_ins.user = user_id AND YEAR(CONVERT_TZ(stippers_check_ins.time, @@global.time_zone, ?)) = stippers_user_card_year.membership_year WHERE ' . $searchPart . $groupByPart . $orderPart;
            $stmt = $conn->prepare($commString);
            
            //Check if statement could be prepared
            if ($stmt) {
                    
                //Because we want to bind an array with parameters we use call_user_func_array
                call_user_func_array(array($stmt, 'bind_param'), $params);
    
                if (!$stmt->execute())
                    throw new UserDBException('Unknown error during statement execution while getting search users.', UserDBException::UNKNOWNERROR);
                else {
                    $result = $stmt->get_result();
                    $searchUsers = array();
                    $i = 0;
                    
                    while ($row = $result->fetch_assoc()) {
                        $searchUsers[$i]['user'] = new User();
                        $searchUsers[$i]['user']->userId = $row['user_id'];
                        if (isset($select['email']) && $select['email'])
                            $searchUsers[$i]['user']->email = $row['email'];
                        if (isset($select['firstName']) && $select['firstName'])
                            $searchUsers[$i]['user']->firstName = $row['first_name'];
                        if (isset($select['lastName']) && $select['lastName'])
                            $searchUsers[$i]['user']->lastName = $row['last_name'];
                        if (isset($select['balance']) && $select['balance'])
                            $searchUsers[$i]['user']->balance = $row['balance'];
                        if (isset($select['phone']) && $select['phone'])
                            $searchUsers[$i]['user']->phone = $row['phone'];
                        if (isset($select['dateofBrith']) && $select['dateOfBirth'])
                            $searchUsers[$i]['user']->dateOfBirth = $row['date_of_birth'];
                        if (isset($select['street']) && $select['street'])
                            $searchUsers[$i]['user']->street = $row['street'];
                        if (isset($select['houseNumber']) && $select['houseNumber'])
                            $searchUsers[$i]['user']->houseNumber = $row['house_number'];
                        if (isset($select['city']) && $select['city'])
                            $searchUsers[$i]['user']->city = $row['city'];
                        if (isset($select['postalCode']) && $select['postalCode'])
                            $searchUsers[$i]['user']->postalCode = $row['postal_code'];
                        if (isset($select['country']) && $select['country'])
                            $searchUsers[$i]['user']->country = $row['country'];
                        if (isset($select['isAdmin']) && $select['isAdmin'])
                            $searchUsers[$i]['user']->isAdmin = $row['is_admin'];
                        /*
                        This is for a future feature.
                        if ($select['isHintManager'])
                            $searchUsers[$i]['user']->isHintManager = $row['is_hint_manager'];
                        */
                        if (isset($select['isUserManager']) && $select['isUserManager'])
                            $searchUsers[$i]['user']->isUserManager = $row['is_user_manager'];
                        if (isset($select['isBrowserManager']) && $select['isBrowserManager'])
                            $searchUsers[$i]['user']->isBrowserManager = $row['is_browser_manager'];
                        if (isset($select['isMoneyManager']) && $select['isMoneyManager'])
                            $searchUsers[$i]['user']->isMoneyManager = $row['is_money_manager'];
                        if (isset($select['creationTime']) && $select['creationTime'])
                            $searchUsers[$i]['user']->creationTime = $row['creation_time'];
                        if (isset($select['membershipYear']) && $select['membershipYear'])
                            $searchUsers[$i]['membershipYear'] = $row['membership_year'];
                        if (isset($select['cardNumber']) && $select['cardNumber'])
                            $searchUsers[$i]['cardNumber'] = $row['card'];
                        if (isset($select['nCheckInsPerYear']) && $select['nCheckInsPerYear'])
                            $searchUsers[$i]['nCheckIns'] = $row['n_check_ins'];
                        
                        $i++;
                    }
                    return $searchUsers;
                }
            }
            else
                throw new UserDBException('Cannot prepare statement.', UserDBException::CANNOTPREPARESTMT);
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
     * Checks if a given password hash is correct for a given user.
     * 
     * @param int $userId userId to check  password hash for
     * @param string $passwordHash password hash to check
     * @return boolean if the given hash is correct
     * @throws Exception generic error for if something goes wrong while talking to the database
     * @throws UserDBException error for if something goes wrong while checking the hash
     */
    public static function isPasswordHashCorrectByUserIdPasswordHash($userId, $passwordHash) {
        try {
            $conn = Database::getConnection();
            $commString = 'SELECT COUNT(*) FROM stippers_users WHERE user_id = ? AND password_hash = ?';
            $stmt = $conn->prepare($commString);
            
            //Check if statement could be prepared
            if ($stmt) {
                
                $stmt->bind_param('is', $userId, $passwordHash);
                
                if (!$stmt->execute())
                    throw new UserDBException('Unknown error during statement execution while checking the user\'s password.', UserDBException::UNKNOWNERROR);
                else {
                    $stmt->bind_result($nResults);
                    
                    if ($stmt->fetch())
                        if ($nResults == 0)
                            return false;
                        else
                            return true;
                    else
                        throw new UserDBException('Unknown error during statement execution while checking the user\'s password.', CheckInDBException::UNKNOWNERROR);
                }
            }
            else
                throw new UserDBException('Cannot prepare statement.', UserDBException::CANNOTPREPARESTMT);
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
     * Gets a basic user by it's card number. A basic user is a user
     * that has only basic properties such as permissions set.
     * 
     * @param int $cardNumber card number of the user to get
     * @return User user for given card number
     * @throws Exception generic error for if something goes wrong while talking to the database
     * @throws UserDBException error for if something goes wrong while getting the user
     */
    public static function getBasicUserByCardNumber($cardNumber) {
        try {
            $conn = Database::getConnection();
            $commString = 'SELECT user_id, email, first_name, last_name FROM stippers_users WHERE user_id = (SELECT user FROM stippers_user_card_year WHERE card = ? AND membership_year = (SELECT YEAR(CONVERT_TZ(NOW(), @@global.time_zone, ?))))';
            $stmt = $conn->prepare($commString);
            
            //Check if statement could be prepared
            if ($stmt) {
                
                $timezone = GlobalConfig::MYSQL_TIME_ZONE;
                $stmt->bind_param('is', $cardNumber, $timezone);
                
                if (!$stmt->execute())
                    throw new UserDBException('Unknown error during statement execution while getting basic user by card number.', UserDBException::UNKNOWNERROR);
                else {
                    $stmt->bind_result($userId, $email, $firstName, $lastName);
                    
                    if ($stmt->fetch()) {
                        $user = new User();
                        $user->userId = $userId;
                        $user->email = $email;
                        $user->firstName = $firstName;
                        $user->lastName = $lastName;
                        return $user;
                    }
                    else
                        throw new UserDBException('No user was found for this card number and year.', UserDBException::NOUSERFORCARDNUMER);
                }
            }
            else
                throw new UserDBException('Cannot prepare statement.', UserDBException::CANNOTPREPARESTMT);
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
     * Gets a user by it's card number.
     * 
     * @param int $cardNumber card number of the user to get
     * @return User user for given card number
     * @throws Exception generic error for if something goes wrong while talking to the database
     * @throws UserDBException error for if something goes wrong while getting the user
     */
    public static function getFullUserByCardNumber($cardNumber) {
        try {
            $conn = Database::getConnection();
            $commString = 'SELECT user_id, email, first_name, last_name, password_hash, balance, phone, DATE_FORMAT(date_of_birth, "%d/%m/%Y") date_of_birth, street, house_number, city, postal_code, country, is_admin, is_hint_manager, is_user_manager, is_browser_manager, is_money_manager, DATE_FORMAT(CONVERT_TZ(creation_time, @@global.time_zone, ?), "%d/%m/%Y %H:%i") creation_time '
                .'FROM stippers_users WHERE user_id = (SELECT user FROM stippers_user_card_year WHERE card = ? AND membership_year = (SELECT YEAR(CONVERT_TZ(NOW(), @@global.time_zone, ?))))';
            $stmt = $conn->prepare($commString);
            
            //Check if statement could be prepared
            if ($stmt) {
                
                $timezone = GlobalConfig::MYSQL_TIME_ZONE;
                $stmt->bind_param('sis', $timezone, $cardNumber, $timezone);
                
                if (!$stmt->execute())
                    throw new UserDBException('Unknown error during statement execution while getting basic user by card number.', UserDBException::UNKNOWNERROR);
                else {
                    $stmt->bind_result($userId, $email, $firstName, $lastName, $passwordHash, $balance, $phone, $dateOfBirth, $street, $houseNumber, $city, $postalCode, $country, $isAdmin, $isHintManager, $isUserManager, $isBrowserManager, $isMoneyManager, $creationTime);
                    
                    if ($stmt->fetch()) {
                        $user = new User();
                        $user->userId = $userId;
                        $user->email = $email;
                        $user->firstName = $firstName;
                        $user->lastName = $lastName;
                        $user->passwordHash = $passwordHash;
                        $user->balance = $balance;
                        $user->phone = $phone;
                        $user->dateOfBirth = $dateOfBirth;
                        $user->street = $street;
                        $user->houseNumber = $houseNumber;
                        $user->city = $city;
                        $user->postalCode = $postalCode;
                        $user->country = $country;
                        $user->isAdmin = $isAdmin;
                        $user->isHintManager = $isHintManager;
                        $user->isUserManager = $isUserManager;
                        $user->isBrowserManager = $isBrowserManager;
                        $user->isMoneyManager = $isMoneyManager;
                        $user->creationTime = $creationTime;
                        return $user;
                    }
                    else
                        throw new UserDBException('No user was found for this card number and year.', UserDBException::NOUSERFORCARDNUMER);
                }
            }
            else
                throw new UserDBException('Cannot prepare statement.', UserDBException::CANNOTPREPARESTMT);
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
     * Gets a basic user by it's email address. 
     * A basic user is a user that has only basic properties 
     * such as permissions set. 
     *  
     * @param string $email email address of the user to get 
     * @return User user for the given email 
     * @throws Exception generic error for if something goes wrong while talking to the database 
     * @throws UserDBException error for if something goes wrong while getting the user 
     */ 
    public static function getBasicUserByEmail($email) {
        try {
            $conn = Database::getConnection();
            $commString = 'SELECT user_id, first_name, last_name, password_hash, is_admin, is_hint_manager, is_user_manager, is_browser_manager, is_money_manager FROM stippers_users WHERE email = ?';
            $stmt = $conn->prepare($commString);
            
            //Check if statement could be prepared
            if ($stmt) {
                
                $stmt->bind_param('s', $email);
                 
                if (!$stmt->execute())
                    throw new UserDBException('Unknown error during statement execution while getting basic user by email.', UserDBException::UNKNOWNERROR);
                else {
                    $stmt->bind_result($userId, $firstName, $lastName, $passwordHash, $isAdmin, $isHintManager, $isUserManager, $isBrowserManager, $isMoneyManager);
                    
                    if ($stmt->fetch()) {
                        $user = new User();
                        $user->userId = $userId;
                        $user->email = $email;
                        $user->firstName = $firstName;
                        $user->lastName = $lastName;
                        $user->passwordHash = $passwordHash;
                        $user->isAdmin = $isAdmin;
                        $user->canManageHints = $isHintManager;
                        $user->isUserManager = $isUserManager;
                        $user->isComputerManager = $isBrowserManager;
                        $user->isMoneyManager = $isMoneyManager;
                        return $user;
                    }
                    else
                        throw new UserDBException('No user was found for this email address.', UserDBException::NOUSERFOREMAIL);
                }
            }
            else
                throw new UserDBException('Cannot prepare statement.', UserDBException::CANNOTPREPARESTMT);
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
     * Gets a basic user by it's email address.
     * A basic user is a user that has only basic properties
     * such as permissions set.
     * Auth means that only users that are a member this year and the admin are returned.
     * 
     * @param string $email email address of the user to get
     * @return User user for the given email
     * @throws Exception generic error for if something goes wrong while talking to the database
     * @throws UserDBException error for if something goes wrong while getting the user
     */
    public static function getAuthUserByEmail($email) {
        try {
            $conn = Database::getConnection();
            $commString = 'SELECT user_id, first_name, last_name, password_hash, is_admin, is_hint_manager, is_user_manager, is_browser_manager, is_money_manager '
                .'FROM stippers_users LEFT JOIN stippers_user_card_year ON user_id = user '
                .'WHERE email = ? AND (membership_year = YEAR(CONVERT_TZ(NOW(), @@global.time_zone, ?)) OR user_id = ?)';
            $stmt = $conn->prepare($commString);
            
            //Check if statement could be prepared
            if ($stmt) {
                
                $timezone = GlobalConfig::MYSQL_TIME_ZONE;
                $adminId = GlobalConfig::ADMIN_ID;
                $stmt->bind_param('sss', $email, $timezone, $adminId);
                
                if (!$stmt->execute())
                    throw new UserDBException('Unknown error during statement execution while getting basic user by email.', UserDBException::UNKNOWNERROR);
                else {
                    $stmt->bind_result($userId, $firstName, $lastName, $passwordHash, $isAdmin, $isHintManager, $isUserManager, $isBrowserManager, $isMoneyManager);
                    
                    if ($stmt->fetch()) {
                        $user = new User();
                        $user->userId = $userId;
                        $user->email = $email;
                        $user->firstName = $firstName;
                        $user->lastName = $lastName;
                        $user->passwordHash = $passwordHash;
                        $user->isAdmin = $isAdmin;
                        $user->canManageHints = $isHintManager;
                        $user->isUserManager = $isUserManager;
                        $user->isComputerManager = $isBrowserManager;
                        $user->isMoneyManager = $isMoneyManager;
                        return $user;
                    }
                    else
                        throw new UserDBException('No user was found for this email address.', UserDBException::NOUSERFOREMAIL);
                }
            }
            else
                throw new UserDBException('Cannot prepare statement.', UserDBException::CANNOTPREPARESTMT);
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
     * Adds a user.
     * 
     * @param User $user user to add
     * @param string $passwordSalt password salt for the user
     * @param int $cardId card number for the user
     * @return ID of inserted user
     * @throws Exception generic error for if something goes wrong while talking to the database
     * @throws UserDBException error for if something goes wrong while adding the user
     */
    public static function addUser($user, $passwordSalt, $cardId) {
        try {
            $conn = Database::getConnection();
            
            $conn->autocommit(false);

            $commString = 'SELECT stippers_nextval("stippers_users_seq")';
            $stmt = $conn->prepare($commString);
            
            //Check if statement could be prepared
            if ($stmt) {
               
                if (!$stmt->execute())
                    throw new UserDBException('Cannot retrieve the next user id.', UserDBException::CANNOTGETNEXTUSERID);
                
                $stmt->bind_result($userId);
                
                if (!$stmt->fetch())
                    throw new UserDBException('Cannot retrieve the next user id.', UserDBException::CANNOTGETNEXTUSERID);
                
                $stmt->close();
                
                $commString = 'INSERT INTO stippers_users (user_id, email, first_name, last_name, password_hash, password_salt, phone, date_of_birth, street, house_number, city, postal_code, country) ' .
                    'VALUES (?, ?, ?, ?, ?, ?, ?, STR_TO_DATE(?, "%d/%m/%Y"), ?, ?, ?, ?, ?)';
                $stmt = $conn->prepare($commString);
                
                //Check if statement could be prepared
                if ($stmt) {
                    
                    $stmt->bind_param('issssssssssss', $userId, $user->email, $user->firstName, $user->lastName, $user->passwordHash, $passwordSalt, $user->phone, $user->dateOfBirth, $user->street, $user->houseNumber, $user->city, $user->postalCode, $user->country);
                    
                    if (!$stmt->execute()) {
                        if ($stmt->errno == 1062)
                            throw new UserDBException('This email address is already used.', UserDBException::EMAILALREADYEXISTS);
                        else
                            throw new UserDBException('Unknown error during statement execution while adding user.', UserDBException::UNKNOWNERROR);
                    }
                    
                    $stmt->close();

                    $commString = 'INSERT INTO stippers_user_card_year (user, card, membership_year) VALUES (?, ?, YEAR(CONVERT_TZ(NOW(), @@global.time_zone, ?)))';
                    $stmt = $conn->prepare($commString);
                    
                    //Check if statement could be prepared
                    if ($stmt) {
                        
                        $timezone = GlobalConfig::MYSQL_TIME_ZONE;
                        $stmt->bind_param('iis', $userId, $cardId, $timezone);
                        
                        if ($stmt->execute())
                            $conn->commit();
                        else {
                            if ($stmt->errno == 1062)
                                throw new UserDBException('This card is already used.', UserDBException::CARDALREADYUSED);
                            else
                                throw new UserDBException('Unknown error during statement execution while adding user.', UserDBException::UNKNOWNERROR);
                        }
                    }
                    else
                        throw new UserDBException('Cannot prepare statement.', UserDBException::CANNOTPREPARESTMT);
                }
                else
                        throw new UserDBException('Cannot prepare statement.', UserDBException::CANNOTPREPARESTMT);
            }
            else
                throw new UserDBException('Cannot prepare statement.', UserDBException::CANNOTPREPARESTMT);
            
            return $userId;
        }
        catch (Exception $ex) {
            if (isset($conn))
                $conn->rollback();
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
     * Updates a user and updates it's card number.
     * 
     * @param User $oldUser old user to check of someone else already updated the user
     * @param User $newUser user with updated data
     * @param int $oldCardNumber old card number
     * @param int $newCardNumber new card number
     * @throws Exception generic error for if something goes wrong while talking to the database
     * @throws UserDBException error for if something goes wrong while updating the user
     */
    public static function updateUserWithCard($oldUser, $newUser, $oldCardNumber, $newCardNumber) {
        try {
            $conn = Database::getConnection();

            $conn->autocommit(false);
            $commString = 'UPDATE stippers_users SET email = ?, first_name = ?, last_name = ?, phone = ?, date_of_birth = STR_TO_DATE(?, "%d/%m/%Y"), street = ?, house_number = ?, city = ?, postal_code = ?, country = ?, is_admin = ?, is_hint_manager = ?, is_user_manager = ?, is_browser_manager = ?, is_money_manager = ? ' .
                'WHERE user_id = ? AND email = ? AND first_name = ? AND last_name = ? AND password_hash = ? AND phone = ? AND date_of_birth = STR_TO_DATE(?, "%d/%m/%Y") AND street = ? AND house_number = ? AND city = ? AND postal_code = ? AND country = ? AND DATE_FORMAT(CONVERT_TZ(creation_time, @@global.time_zone, ?), "%d/%m/%Y %H:%i") = ? AND is_admin = ? AND is_hint_manager = ? AND is_user_manager = ? AND is_browser_manager = ? AND is_money_manager = ?';
            $stmt = $conn->prepare($commString);
            
            //Check if statement could be prepared
            if ($stmt) {
               
                $timezone = GlobalConfig::MYSQL_TIME_ZONE;
                $stmt->bind_param('ssssssssssiiiiiissssssssssssssiiiii', $newUser->email, $newUser->firstName, $newUser->lastName, $newUser->phone, $newUser->dateOfBirth, $newUser->street, $newUser->houseNumber, $newUser->city, $newUser->postalCode, $newUser->country, $newUser->isAdmin, $newUser->isHintManager, $newUser->isUserManager, $newUser->isBrowserManager, $newUser->isMoneyManager,
                $oldUser->userId, $oldUser->email, $oldUser->firstName, $oldUser->lastName, $oldUser->passwordHash, $oldUser->phone, $oldUser->dateOfBirth, $oldUser->street, $oldUser->houseNumber, $oldUser->city, $oldUser->postalCode, $oldUser->country, $timezone, $oldUser->creationTime, $oldUser->isAdmin, $oldUser->isHintManager, $oldUser->isUserManager, $oldUser->isBrowserManager, $oldUser->isMoneyManager);
                
                if (!$stmt->execute())
                    if ($stmt->errno == 1062)
                        throw new UserDBException('Cannot update user.', UserDBException::EMAILALREADYEXISTS);
                    else
                        throw new UserDBException('Unknown error during statement execution while updating user.', UserDBException::UNKNOWNERROR);
    
                $stmt->close();
                
                $commString = 'UPDATE stippers_user_card_year SET card = ? WHERE user = ? AND card = ? AND membership_year = YEAR(CONVERT_TZ(NOW(), @@global.time_zone, ?))';
                $stmt = $conn->prepare($commString);
                
                //Check if statement could be prepared
                if ($stmt) {
                    
                    $stmt->bind_param('iiis', $newCardNumber, $oldUser->userId, $oldCardNumber, $timezone);
                    
                    if ($stmt->execute())
                        $conn->commit();
                    else {
                        if ($stmt->errno == 1062)
                            throw new UserDBException('This card is already used.', UserDBException::CARDALREADYUSED);
                        else
                            throw new UserDBException('Unknown error during statement execution while adding user.', UserDBException::UNKNOWNERROR);
                    }
                }
                else
                    throw new UserDBException('Cannot prepare statement.', UserDBException::CANNOTPREPARESTMT);
            }
            else
                throw new UserDBException('Cannot prepare statement.', UserDBException::CANNOTPREPARESTMT);
        }
        catch (Exception $ex) {
            if (isset($conn))
                $conn->rollback();
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
     * Updates a user.
     * 
     * @param User $oldUser old user to check of someone else already updated the user
     * @param User $newUser user with updated data
     * @throws Exception generic error for if something goes wrong while talking to the database
     * @throws UserDBException error for if something goes wrong while updating the user
     */
    public static function updateUser($oldUser, $newUser) {
        try {
            $conn = Database::getConnection();
            $commString = 'UPDATE stippers_users SET email = ?, first_name = ?, last_name = ?, phone = ?, date_of_birth = STR_TO_DATE(?, "%d/%m/%Y"), street = ?, house_number = ?, city = ?, postal_code = ?, country = ?, is_admin = ?, is_hint_manager = ?, is_user_manager = ?, is_browser_manager = ?, is_money_manager = ? ' .
                'WHERE user_id = ? AND email = ? AND first_name = ? AND last_name = ? AND password_hash = ? AND balance = ? AND phone = ? AND date_of_birth = STR_TO_DATE(?, "%d/%m/%Y") AND street = ? AND house_number = ? AND city = ? AND postal_code = ? AND country = ? AND DATE_FORMAT(CONVERT_TZ(creation_time, @@global.time_zone, ?), "%d/%m/%Y %H:%i") = ? AND is_admin = ? AND is_hint_manager = ? AND is_user_manager = ? AND is_browser_manager = ? AND is_money_manager = ?';
            $stmt = $conn->prepare($commString);
            
            //Check if statement could be prepared
            if ($stmt) {
               
                $timezone = GlobalConfig::MYSQL_TIME_ZONE;
                $stmt->bind_param('ssssssssssiiiiiissssssssssssssiiiii', $newUser->email, $newUser->firstName, $newUser->lastName, $newUser->phone, $newUser->dateOfBirth, $newUser->street, $newUser->houseNumber, $newUser->city, $newUser->postalCode, $newUser->country, $newUser->isAdmin, $newUser->isHintManager, $newUser->isUserManager, $newUser->isBrowserManager, $newUser->isMoneyManager,
                $oldUser->userId, $oldUser->email, $oldUser->firstName, $oldUser->lastName, $oldUser->passwordHash, $oldUser->balance, $oldUser->phone, $oldUser->dateOfBirth, $oldUser->street, $oldUser->houseNumber, $oldUser->city, $oldUser->postalCode, $oldUser->country, $timezone, $oldUser->creationTime, $oldUser->isAdmin, $oldUser->isHintManager, $oldUser->isUserManager, $oldUser->isBrowserManager, $oldUser->isMoneyManager);
                
                if (!$stmt->execute()) {
                    if ($stmt->errno == 1062)
                        throw new UserDBException('Cannot update user.', UserDBException::EMAILALREADYEXISTS);
                    else
                        throw new UserDBException('Unknown error during statement execution while updating user.', UserDBException::UNKNOWNERROR);
                }
                else if ($stmt->affected_rows == 0)
                    throw new UserDBException('The user is out of date, someone else has probably already changed the user.', UserDBException::USEROUTOFDATE);
            }
            else
                throw new UserDBException('Cannot prepare statement.', UserDBException::CANNOTPREPARESTMT);
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
     * Updates a user's password.
     * 
     * @param User $oldUser old user to check of someone else already updated the user
     * @param string $newPasswordHash new password hash
     * @throws Exception generic error for if something goes wrong while talking to the database
     * @throws UserDBException error for if something goes wrong while updating the password
     */
    public static function updatePassword($oldUser, $newPasswordHash) {
        try {
            $conn = Database::getConnection();
            $commString = 'UPDATE stippers_users SET password_hash = ? ' .
                'WHERE user_id = ? AND email = ? AND first_name = ? AND last_name = ? AND password_hash = ? AND balance = ? AND phone = ? AND date_of_birth = STR_TO_DATE(?, "%d/%m/%Y") AND street = ? AND house_number = ? AND city = ? AND postal_code = ? AND country = ? AND DATE_FORMAT(CONVERT_TZ(creation_time, @@global.time_zone, ?), "%d/%m/%Y %H:%i") = ? AND is_admin = ? AND is_hint_manager = ? AND is_user_manager = ? AND is_browser_manager = ? AND is_money_manager = ?';
            $stmt = $conn->prepare($commString);
            
            //Check if statement could be prepared
            if ($stmt) {
               
               $timezone = GlobalConfig::MYSQL_TIME_ZONE;
               $stmt->bind_param('sissssssssssssssiiiii', $newPasswordHash, $oldUser->userId, $oldUser->email, $oldUser->firstName, $oldUser->lastName, $oldUser->passwordHash, $oldUser->balance, $oldUser->phone, $oldUser->dateOfBirth, $oldUser->street, $oldUser->houseNumber, $oldUser->city, $oldUser->postalCode, $oldUser->country, $timezone, $oldUser->creationTime, $oldUser->isAdmin, $oldUser->isHintManager, $oldUser->isUserManager, $oldUser->isBrowserManager, $oldUser->isMoneyManager);
               
               if (!$stmt->execute())
                   throw new UserDBException('Unknown error during statement execution while updating user.', UserDBException::UNKNOWNERROR);
               else if ($stmt->affected_rows == 0)
                   throw new UserDBException('The user is out of date, someone else has probably already changed the user.', UserDBException::USEROUTOFDATE);
            
            }
            else
               throw new UserDBException('Cannot prepare statement.', UserDBException::CANNOTPREPARESTMT);
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
     * Sets a new password for the user who's email address is given.
     * 
     * @param string $email email of the user to set new password for
     * @param string $newPasswordHash new password hash
     * @throws Exception generic error for if something goes wrong while talking to the database
     * @throws UserDBException error for if something goes wrong while updating the password
     */
    public static function resetPassword($email, $newPasswordHash) {
        try {
            $conn = Database::getConnection();
            $commString = 'UPDATE stippers_users SET password_hash = ? WHERE email = ?';
            $stmt = $conn->prepare($commString);
            
            //Check if statement could be prepared
            if ($stmt) {
               
                $stmt->bind_param('ss', $newPasswordHash, $email);
                if (!$stmt->execute())
                    throw new UserDBException('Unknown error during statement execution while updating user.', UserDBException::UNKNOWNERROR);
                else if ($stmt->affected_rows == 0)
                    throw new UserDBException('The user is out of date, someone else has probably already changed the user.', UserDBException::USEROUTOFDATE);
            }
            else
               throw new UserDBException('Cannot prepare statement.', UserDBException::CANNOTPREPARESTMT);
            
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
     * Renews a user's membership.
     * 
     * @param User $oldUser old user to check of someone else already updated the user
     * @param User $newUser user with updated data
     * @param type $cardNumber new card number
     * @throws Exception generic error for if something goes wrong while talking to the database
     * @throws UserDBException error for if something goes wrong while renewing the user's membership
     */
    public static function renewMembership($oldUser, $newUser, $cardNumber) {
        try {
            $conn = Database::getConnection();
            $conn->autocommit(false);
            $commString = 'UPDATE stippers_users SET email = ?, first_name = ?, last_name = ?, phone = ?, date_of_birth = STR_TO_DATE(?, "%d/%m/%Y"), street = ?, house_number = ?, city = ?, postal_code = ?, country = ? ' .
                'WHERE user_id = ? AND email = ? AND first_name = ? AND last_name = ? AND password_hash = ? AND balance = ? AND phone = ? AND date_of_birth = STR_TO_DATE(?, "%d/%m/%Y") AND street = ? AND house_number = ? AND city = ? AND postal_code = ? AND country = ? AND DATE_FORMAT(CONVERT_TZ(creation_time, @@global.time_zone, ?), "%d/%m/%Y %H:%i") = ? AND is_admin = ? AND is_hint_manager = ? AND is_user_manager = ? AND is_browser_manager = ? AND is_money_manager = ?';
            $stmt = $conn->prepare($commString);
            
            //Check if statement could be prepared
            if ($stmt) {
                
                $timezone = GlobalConfig::MYSQL_TIME_ZONE;
                $stmt->bind_param('ssssssssssissssdsssssssssiiiii', $newUser->email, $newUser->firstName, $newUser->lastName, $newUser->phone, $newUser->dateOfBirth, $newUser->street, $newUser->houseNumber, $newUser->city, $newUser->postalCode, $newUser->country, $oldUser->userId, $oldUser->email, $oldUser->firstName, $oldUser->lastName, $oldUser->passwordHash, $oldUser->balance, $oldUser->phone, $oldUser->dateOfBirth, $oldUser->street, $oldUser->houseNumber, $oldUser->city, $oldUser->postalCode, $oldUser->country, $timezone, $oldUser->creationTime, $oldUser->isAdmin, $oldUser->isHintManager, $oldUser->isUserManager, $oldUser->isBrowserManager, $oldUser->isMoneyManager);
                
                if (!$stmt->execute()) {
                    if ($stmt->errno == 1062)
                        throw new UserDBException('Cannot update user.', UserDBException::EMAILALREADYEXISTS);
                    else
                        throw new UserDBException('Unknown error during statement execution while updating user.', UserDBException::UNKNOWNERROR);
                }
                else if ($stmt->affected_rows == 0)
                    throw new UserDBException('The user is out of date, someone else has probably already changed the user.', UserDBException::USEROUTOFDATE);
    
                $stmt->close();
                
                $commString = 'INSERT INTO stippers_user_card_year (user, card, membership_year) VALUES (?, ?, YEAR(CONVERT_TZ(NOW(), @@global.time_zone, ?)))';
                $stmt = $conn->prepare($commString);
                
                //Check if statement could be prepared
                if ($stmt) {
                
                    $stmt->bind_param('iis', $oldUser->userId, $cardNumber, $timezone);
                    
                    if ($stmt->execute())
                        $conn->commit();
                    else {
                        if ($stmt->errno == 1062) {
                            if (substr($stmt->error, strlen($stmt->error) - 9) == '\'PRIMARY\'')
                                throw new UserDBException('This user is already a member this year.', UserDBException::USERALREADYMEMBER);
                            else
                                throw new UserDBException('This card is already used.', UserDBException::CARDALREADYUSED);
                        }
                        else
                            throw new UserDBException('Unknown error during statement execution while renewing user.', UserDBException::UNKNOWNERROR);
                    }
                }
                else
                    throw new UserDBException('Cannot prepare statement.', UserDBException::CANNOTPREPARESTMT);
            }
            else
               throw new UserDBException('Cannot prepare statement.', UserDBException::CANNOTPREPARESTMT);
        }
        catch (Exception $ex) {
            if (isset($conn))
                $conn->rollback();
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