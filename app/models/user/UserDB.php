<?php
/**
 * Created by PhpStorm.
 * User: Stan
 * Date: 27/07/14
 * Time: 14:23
 */

require_once __DIR__.'/../../helperClasses/database/Database.php';
require_once __DIR__.'/../../helperClasses/database/DatabaseException.php';
require_once 'User.php';
require_once 'UserDBException.php';

abstract class UserDB
{
    public static function getAllBasicUsers() {
        try {
            $conn = Database::getConnection();
            $commString = 'SELECT email, first_name, last_name, password_hash, is_admin, is_hint_manager, is_user_manager, is_authorized_browser_manager ' .
                'FROM stippers_users';
            $stmt = $conn->prepare($commString);

            if (!$stmt->execute())
                throw new UserDBException('Unknown error during statement execution while getting the user.', UserDBException::UNKNOWNERROR);
            else {
                $users = array();
                $i = 0;
                $stmt->bind_result($email, $firstName, $lastName, $passwordHash, $isAdmin, $isHintManager, $isUserManager, $isAuthorizedBrowserManager);
                
                while ($stmt->fetch()) {
                    $users[$i] = new User();
                    $users[$i]->email = $email;
                    $users[$i]->firstName = $firstName;
                    $users[$i]->lastName = $lastName;
                    $users[$i]->passwordHash = $passwordHash;
                    $users[$i]->isAdmin = $isAdmin;
                    $users[$i]->isHintManager = $isHintManager;
                    $users[$i]->isUserManager = $isUserManager;
                    $users[$i]->isAuthorizedBrowserManager = $isAuthorizedBrowserManager;
                    $i++;
                }
                
                return $users;
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

    public static function getFullUserById($userId) {
        try {
            $conn = Database::getConnection();
            $commString = 'SELECT email, first_name, last_name, password_hash, balance, phone, DATE_FORMAT(date_of_birth, "%d/%m/%Y") date_of_birth, street, house_number, city, postal_code, country, bartender_info, is_admin, is_hint_manager, is_user_manager, is_authorized_browser_manager, DATE_FORMAT(CONVERT_TZ(creation_time, @@global.time_zone, ?), "%d/%m/%Y %H:%i") creation_time ' .
                'FROM stippers_users WHERE user_id = ?';
            $stmt = $conn->prepare($commString);
            $timezone = Database::TIMEZONE;
            $stmt->bind_param('si', $timezone, $userId);

            if (!$stmt->execute())
                throw new UserDBException('Unknown error during statement execution while getting the user.', UserDBException::UNKNOWNERROR);
            else {
                $stmt->bind_result($email, $firstName, $lastName, $passwordHash, $balance, $phone, $dateOfBirth, $street, $houseNumber, $city, $postalCode, $country, $bartenderInfo, $isAdmin, $isHintManager, $isUserManager, $isAuthorizedBrowserManager, $creationTime);
                
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
                    $user->bartenderInfo = $bartenderInfo;
                    $user->isAdmin = $isAdmin;
                    $user->isHintManager = $isHintManager;
                    $user->isUserManager = $isUserManager;
                    $user->isAuthorizedBrowserManager = $isAuthorizedBrowserManager;
                    $user->creationTime = $creationTime;
                    return $user;
                }
                else
                    throw new UserDBException('No user was found for this is id.', UserDBException::NOUSERFORID);
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

    public static function getBasicUserById($userId) {
        try {
            $conn = Database::getConnection();
            $commString = 'SELECT email, first_name, last_name, password_hash, is_admin, is_hint_manager, is_user_manager, is_authorized_browser_manager ' .
                'FROM stippers_users WHERE user_id = ?';
            $stmt = $conn->prepare($commString);
            $stmt->bind_param('i', $userId);

            if (!$stmt->execute())
                throw new UserDBException('Unknown error during statement execution while getting the user.', UserDBException::UNKNOWNERROR);
            else {
                $stmt->bind_result($email, $firstName, $lastName, $passwordHash, $isAdmin, $isHintManager, $isUserManager, $isAuthorizedBrowserManager);
                
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
                    $user->isAuthorizedBrowserManager = $isAuthorizedBrowserManager;
                    return $user;
                }
                else
                    throw new UserDBException('No user was found for this is id.', UserDBException::NOUSERFORID);
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

    public static function getPasswordSaltByEmail($email) {
        try {
            $conn = Database::getConnection();
            $commString = 'SELECT password_salt FROM stippers_users WHERE email = ?';
            $stmt = $conn->prepare($commString);
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

    public static function getPasswordSaltByUserId($userId) {
        try {
            $conn = Database::getConnection();
            $commString = 'SELECT password_salt FROM stippers_users WHERE user_id = ?';
            $stmt = $conn->prepare($commString);
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

    public static function getSearchUsers($select, $search, $options) {
        try {
            $selectPart = 'user_id';
            $orderPart = ' ORDER BY null';
            $groupByPart = ' GROUP BY user_id';
            $params['types'] = '';

            if ($options['orderByBirthday']) {
                $selectPart .= ', DATE_FORMAT(date_of_birth, "%m/%d") birthday';
                $orderPart .= ', birthday ASC';
                $groupByPart .= ', birthday';
            }
            if ($select['lastName']) {
                $selectPart .= ', last_name';
                $orderPart .= ', last_name';
                $groupByPart .= ', last_name';
            }
            if ($select['firstName']) {
                $selectPart .= ', first_name';
                $orderPart .= ', first_name';
                $groupByPart .= ', first_name';
            }
            if ($select['membershipYear']) {
                $selectPart .= ', stippers_user_card_year.membership_year';
                $orderPart .= ', membership_year DESC';
                $groupByPart .= ', membership_year';
            }
            if ($select['street']) {
                $selectPart .= ', street';
                $orderPart .= ', street';
                $groupByPart .= ', street';
            }
            if ($select['houseNumber']) {
                $selectPart .= ', house_number';
                $orderPart .= ', house_number';
                $groupByPart .= ', house_number';
            }
            if ($select['city']) {
                $selectPart .= ', city';
                $orderPart .= ', city';
                $groupByPart .= ', city';
            }
            if ($select['postalCode']) {
                $selectPart .= ', postal_code';
                $orderPart .= ', postal_code';
                $groupByPart .= ', postal_code';
            }
            if ($select['country']) {
                $selectPart .= ', country';
                $orderPart .= ', country';
                $groupByPart .= ', country';
            }
            if ($select['email']) {
                $selectPart .= ', email';
                $orderPart .= ', email';
                $groupByPart .= ', email';
            }
            if ($select['balance']) {
                $selectPart .= ', balance';
                $groupByPart .= ', balance';
            }
            if ($select['phone']) {
                $selectPart .= ', phone';
                $groupByPart .= ', phone';
            }
            if ($select['dateOfBirth']) {
                $selectPart .= ', DATE_FORMAT(date_of_birth, "%d/%m/%Y") date_of_birth';
                $groupByPart .= ', date_of_birth';
            }
            if ($select['isAdmin']) {
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
            if ($select['isUserManager']) {
                $selectPart .= ', is_user_manager';
                $groupByPart .= ', is_user_manager';
            }
            if ($select['isAuthorizedBrowserManager']) {
                $selectPart .= ', is_authorized_browser_manager';
                $groupByPart .= ', is_authorized_browser_manager';
            }
            if ($select['creationTime']) {
                $selectPart .= ', DATE_FORMAT(CONVERT_TZ(creation_time, @@global.time_zone, ?), "%d/%m/%Y %H:%i") creation_time';
                $params['types'] .= 's';
                $timezone = Database::TIMEZONE;
                $params['timezone'] = &$timezone;
                $groupByPart .= ', creation_time';
            }
            if ($select['nCheckInsPerYear']) {
                $selectPart .= ', COUNT(*) n_check_ins';
                //Don't group by this! This is what we need 'per group'!
            }
            if ($select['cardNumber']) {
                $selectPart .= ', card';
                $groupByPart .= ', card';
            }
            $searchPart = 'email LIKE ? AND first_name LIKE ? AND last_name LIKE ? AND balance LIKE ? AND phone LIKE ? AND date_of_birth LIKE ? AND street LIKE ? AND house_number LIKE ? AND city LIKE ? AND postal_code LIKE ? AND country LIKE ? AND card LIKE ? AND stippers_user_card_year.membership_year LIKE ? ';
            $params['types'] .= 'sssssssssssss';
            $likeStringEmail = '%' . $search['email'] . '%';
            $params['email'] = &$likeStringEmail;
            $likeStringFirstName = '%' . $search['firstName'] . '%';
            $params['firstName'] = &$likeStringFirstName;
            $likeStringLastName = '%' . $search['lastName'] . '%';
            $params['lastName'] = &$likeStringLastName;
            $likeStringBalance = '%' . $search['balance'] . '%';
            $params['balance'] = &$likeStringBalance;
            $likeStringPhone = '%' . $search['phone'] . '%';
            $params['phone'] = &$likeStringPhone;
            $likeStringDateOfBirth = '%' . $search['dateOfBirth'] . '%';
            $params['datefBirth'] = &$likeStringDateOfBirth;
            $likeStringStreet = '%' . $search['street'] . '%';
            $params['street'] = &$likeStringStreet;
            $likeStringHouseNumber = '%' . $search['houseNumber'] . '%';
            $params['houseNumber'] = &$likeStringHouseNumber;
            $likeStringCity = '%' . $search['city'] . '%';
            $params['city'] = &$likeStringCity;
            $likeStringPostalCode = '%' . $search['postalCode'] . '%';
            $params['postalCode'] = &$likeStringPostalCode;
            $likeStringCountry = '%' . $search['country'] . '%';
            $params['country'] = &$likeStringCountry;
            $likeStringCardNumber = '%' . $search['cardNumber'] . '%';
            $params['cardNumber'] = &$likeStringCardNumber;
            $likeStringMembershipYear = '%' . $search['membershipYear'] . '%';
            $params['membershipYear'] = &$likeStringMembershipYear;

            if ($search['isAdmin'] != '') {
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
            if ($search['isUserManager'] != '') {
                $params['types'] .= 'i';
                $searchPart .= ' AND is_user_manager = ?';
                $likeStringIsUserManager = $search['isUserManager'];
                $params['isUserManager'] = &$likeStringIsUserManager;
            }
            if ($search['isAuthorizedBrowserManager'] != '') {
                $params['types'] .= 'i';
                $searchPart .= ' AND is_authorized_browser_manager = ?';
                $likeStringIsAuthorizedBrowserManager = $search['isAuthorizedBrowserManager'];
                $params['isAuthorizedBrowserManager'] = &$likeStringIsAuthorizedBrowserManager;
            }
            if (isset($search['onlyLastYear']) && $search['onlyLastYear']) {
                $searchPart .= ' AND stippers_user_card_year.membership_year >= ANY (SELECT membership_year FROM stippers_user_card_year WHERE user_id = user)';
            }

            $conn = Database::getConnection();
            $commString = 'SELECT ' . $selectPart . ' FROM stippers_users INNER JOIN stippers_user_card_year ON stippers_user_card_year.user = user_id LEFT JOIN stippers_check_ins ON stippers_check_ins.user = user_id AND YEAR(stippers_check_ins.time) = stippers_user_card_year.membership_year WHERE ' . $searchPart . $groupByPart . $orderPart;
            $stmt = $conn->prepare($commString);
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
                    if ($select['email'])
                        $searchUsers[$i]['user']->email = $row['email'];
                    if ($select['firstName'])
                        $searchUsers[$i]['user']->firstName = $row['first_name'];
                    if ($select['lastName'])
                        $searchUsers[$i]['user']->lastName = $row['last_name'];
                    if ($select['balance'])
                        $searchUsers[$i]['user']->balance = $row['balance'];
                    if ($select['phone'])
                        $searchUsers[$i]['user']->phone = $row['phone'];
                    if ($select['dateOfBirth'])
                        $searchUsers[$i]['user']->dateOfBirth = $row['date_of_birth'];
                    if ($select['street'])
                        $searchUsers[$i]['user']->street = $row['street'];
                    if ($select['houseNumber'])
                        $searchUsers[$i]['user']->houseNumber = $row['house_number'];
                    if ($select['city'])
                        $searchUsers[$i]['user']->city = $row['city'];
                    if ($select['postalCode'])
                        $searchUsers[$i]['user']->postalCode = $row['postal_code'];
                    if ($select['country'])
                        $searchUsers[$i]['user']->country = $row['country'];
                    if ($select['isAdmin'])
                        $searchUsers[$i]['user']->isAdmin = $row['is_admin'];
                    /*
                    This is for a future feature.
                    if ($select['isHintManager'])
                        $searchUsers[$i]['user']->isHintManager = $row['is_hint_manager'];
                    */
                    if ($select['isUserManager'])
                        $searchUsers[$i]['user']->isUserManager = $row['is_user_manager'];
                    if ($select['isAuthorizedBrowserManager'])
                        $searchUsers[$i]['user']->isAuthorizedBrowserManager = $row['is_authorized_browser_manager'];
                    if ($select['creationTime'])
                        $searchUsers[$i]['user']->creationTime = $row['creation_time'];
                    if ($select['membershipYear'])
                        $searchUsers[$i]['membershipYear'] = $row['membership_year'];
                    if ($select['cardNumber'])
                        $searchUsers[$i]['cardNumber'] = $row['card'];
                    if ($select['nCheckInsPerYear'])
                        $searchUsers[$i]['nCheckIns'] = $row['n_check_ins'];
                    
                    $i++;
                }
                return $searchUsers;
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

    public static function isPasswordHashCorrectByUserIdPasswordHash($userId, $passwordHash) {
        try {
            $conn = Database::getConnection();
            $commString = 'SELECT COUNT(*) FROM stippers_users WHERE user_id = ? AND password_hash = ?';
            $stmt = $conn->prepare($commString);
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

    public static function getCardNumberByUserId($userId) {
        $conn = Database::getConnection();

        $commString = 'SELECT card FROM stippers_user_card_year WHERE user = @user AND year = YEAR(NOW())';
        $comm = $conn->prepare($commString);
        $comm->bind_param('i', $userId);

        $comm->execute();
        $result = $comm->get_result();

        $comm->close();
        $conn->kill($conn->thread_id);
        $conn->close();

        if ($row = $result->fetch_row())
            return $row[0];
        else
            return null;
    }

    public static function getBasicUserByCardNumber($cardNumber) {
        try {
            $conn = Database::getConnection();
            $commString = 'SELECT user_id, email, first_name, last_name FROM stippers_users WHERE user_id = (SELECT user FROM stipers_user_card_year WHERE card = ? AND membership_year = (SELECT YEAR(NOW())))';
            $stmt = $conn->prepare($commString);
            $stmt->bind_param('i', $cardNumber);
            
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

    public static function getBasicUserByEmailPasswordHash($email, $passwordHash) {
        try {
            $conn = Database::getConnection();
            $commString = 'SELECT user_id, first_name, last_name, is_admin, is_hint_manager, is_user_manager, is_authorized_browser_manager FROM stippers_users WHERE email = ? AND password_hash = ?';
            $stmt = $conn->prepare($commString);
            $stmt->bind_param('ss', $email, $passwordHash);
            
            if (!$stmt->execute())
                throw new UserDBException('Unknown error during statement execution while getting basic user by email address and password.', UserDBException::UNKNOWNERROR);
            else {
                $stmt->bind_result($userId, $firstName, $lastName, $isAdmin, $isHintManager, $isUserManager, $isAuthorizedComputerManager);
                
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
                    $user->isAuthorizedComputerManager = $isAuthorizedComputerManager;
                    return $user;
                }
                else
                    throw new UserDBException('No user was found for this email address and password.', UserDBException::NOUSERFOREMAILPASSWORD);
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

    public static function getUserByEmailPassword($email, $passwordHash) {
        $user = null;

        $conn = Database::getConnection();
        $commString = 'SELECT user_id, first_name, last_name, balance, phone, DATE_FORMAT(date_of_birth, "%d/%m/%Y"), street, house_number, city, postal_code, country, DATE_FORMAT(CONVERT_TZ(creation_time, @@global.time_zone, ?), "%d/%m/%Y %H:%i") creation_time, is_admin, is_hint_manager, is_user_manager, is_authorized_browser_manager ' .
            'FROM stippers_users WHERE email = ? AND password_hash = ?';

        $stmt = $conn->prepare($commString);
        $timezone = Database::TIMEZONE;
        $stmt->bind_param('sss', $timezone, $email, $passwordHash);

        $stmt->execute();
        $stmt->bind_result($userId, $firstName, $lastName, $balance, $phone, $dateOfBirth, $street, $houseNumber, $city, $postalCode, $country, $creationTime, $isAdmin, $isHintManager, $isUserManager, $isAuthorizedBrowserManager);

        if ($stmt->fetch()) {
            $user = new User();
            $user->userId = $userId;
            $user->firstName = $firstName;
            $user->lastName = $lastName;
            $user->email = $email;
            $user->passwordHash = $passwordHash;
            $user->balance = $balance;
            $user->phone = $phone;
            $user->dateOfBirth = $dateOfBirth;
            $user->street = $street;
            $user->houseNumber = $houseNumber;
            $user->city = $city;
            $user->postalCode = $postalCode;
            $user->country = $country;
            $user->creationTime = $creationTime;
            $user->isAdmin = $isAdmin;
            $user->isHintManager = $isHintManager;
            $user->isUserManager = $isUserManager;
            $user->isAuthorizedBrowserManager = $isAuthorizedBrowserManager;
        }

        $stmt->close();
        $conn->kill($conn->thread_id);
        $conn->close();

        return $user;
    }

    public static function addUser($user, $passwordSalt, $cardId) {
        try {
            $conn = Database::getConnection();

            $conn->autocommit(false);

            $commString = 'SELECT members_nextval("stippers_users_seq")';
            $stmt = $conn->prepare($commString);
            
            if (!$stmt->execute())
                throw new UserDBException('Cannot retrieve the next user id.', UserDBException::CANNOTGETNEXTUSERID);
            
            $stmt->bind_result($userId);
            
            if (!$stmt->fetch())
                throw new UserDBException('Cannot retrieve the next user id.', UserDBException::CANNOTGETNEXTUSERID);
            
            $stmt->close();

            $commString = 'INSERT INTO stippers_users (user_id, email, first_name, last_name, password_hash, password_salt, phone, date_of_birth, street, house_number, city, postal_code, country) ' .
                'VALUES (?, ?, ?, ?, ?, ?, STR_TO_DATE(?, "%d/%m/%Y"), ?, ?, ?, ?, ?)';
            $stmt = $conn->prepare($commString);
            $stmt->bind_param('issssssssssss', $userId, $user->email, $user->firstName, $user->lastName, $user->passwordHash, $passwordSalt, $user->phone, $user->dateOfBirth, $user->street, $user->houseNumber, $user->city, $user->postalCode, $user->country);
            
            if (!$stmt->execute())
                if ($stmt->errno == 1062)
                    throw new UserDBException('This email address is already used.', UserDBException::EMAILALREADYEXISTS);
                else
                    throw new UserDBException('Unknown error during statement execution while adding user.', UserDBException::UNKNOWNERROR);
            $stmt->close();

            //Inserting 0000 for the year is a dirty hack to work around a bug in MySQL < 5.7.1.
            //Anywhere in the code where the year should go to the default (current year) you should insert 0000 instead of NULL.
            $commString = 'INSERT INTO stippers_user_card_year (user, card, membership_year) VALUES (?, ?, 0000)';
            $stmt = $conn->prepare($commString);
            $stmt->bind_param('ii', $userId, $cardId);
            
            if ($stmt->execute())
                $conn->commit();
            else {
                if ($stmt->errno == 1062)
                    throw new UserDBException('This card is already used.', UserDBException::CARDALREADYUSED);
                else
                    throw new UserDBException('Unknown error during statement execution while adding user.', UserDBException::UNKNOWNERROR);
            }
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

    public static function updateUserWithCard($oldUser, $newUser, $oldCardNumber, $newCardNumber) {
        try {
            $conn = Database::getConnection();

            $conn->autocommit(false);
            $commString = 'UPDATE stippers_users SET email = ?, first_name = ?, last_name = ?, phone = ?, date_of_birth = STR_TO_DATE(?, "%d/%m/%Y"), street = ?, house_number = ?, city = ?, postal_code = ?, country = ?, is_admin = ?, is_hint_manager = ?, is_user_manager = ?, is_authorized_browser_manager = ? ' .
                'WHERE user_id = ? AND email = ? AND first_name = ? AND last_name = ? AND password_hash = ? AND phone = ? AND date_of_birth = STR_TO_DATE(?, "%d/%m/%Y") AND street = ? AND house_number = ? AND city = ? AND postal_code = ? AND country = ? AND DATE_FORMAT(CONVERT_TZ(creation_time, @@global.time_zone, ?), "%d/%m/%Y %H:%i") = ? AND is_admin = ? AND is_hint_manager = ? AND is_user_manager = ? AND is_authorized_browser_manager = ?';
            $stmt = $conn->prepare($commString);
            $timezone = Database::TIMEZONE;
            $stmt->bind_param('ssssssssssiiiiissssssssssssssiiii', $newUser->email, $newUser->firstName, $newUser->lastName, $newUser->phone, $newUser->dateOfBirth, $newUser->street, $newUser->houseNumber, $newUser->city, $newUser->postalCode, $newUser->country, $newUser->isAdmin, $newUser->is_hint_manager, $newUser->is_user_manager, $newUser->is_authorized_browser_manager, $oldUser->userId, $oldUser->email, $oldUser->firstName, $oldUser->lastName, $oldUser->passwordHash, $oldUser->phone, $oldUser->dateOfBirth, $oldUser->street, $oldUser->houseNumber, $oldUser->city, $oldUser->postalCode, $oldUser->country, $timezone, $oldUser->creationTime, $oldUser->isAdmin, $oldUser->isHintManager, $oldUser->isUserManager, $oldUser->isAuthorizedBrowserManager);
            
            if (!$stmt->execute())
                if ($stmt->errno == 1062)
                    throw new UserDBException('Cannot update user.', UserDBException::EMAILALREADYEXISTS);
                else
                    throw new UserDBException('Unknown error during statement execution while updating user.', UserDBException::UNKNOWNERROR);

            $stmt->close();
            //Inserting 0000 for the year is a dirty hack to work around a bug in MySQL < 5.7.1.
            //Anywhere in the code where the year should go to the default (current year) you should insert 0000 instead of NULL.
            $commString = 'UPDATE stippers_user_card_year SET card = ? WHERE user = ? AND card = ? AND membership_year = 0000';
            $stmt = $conn->prepare($commString);
            $stmt->bind_param('iii', $newCardNumber, $oldUser->userId, $oldCardNumber);
            
            if ($stmt->execute())
                $conn->commit();
            else {
                if ($stmt->errno == 1062)
                    throw new UserDBException('This card is already used.', UserDBException::CARDALREADYUSED);
                else
                    throw new UserDBException('Unknown error during statement execution while adding user.', UserDBException::UNKNOWNERROR);
            }
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

    public static function updateUser($oldUser, $newUser) {
        try {
            $conn = Database::getConnection();
            $commString = 'UPDATE stippers_users SET email = ?, first_name = ?, last_name = ?, phone = ?, date_of_birth = STR_TO_DATE(?, "%d/%m/%Y"), street = ?, house_number = ?, city = ?, postal_code = ?, country = ?, is_admin = ?, is_hint_manager = ?, is_user_manager = ?, is_authorized_browser_manager = ? ' .
                'WHERE user_id = ? AND email = ? AND first_name = ? AND last_name = ? AND password_hash = ? AND balance = ? AND phone = ? AND date_of_birth = STR_TO_DATE(?, "%d/%m/%Y") AND street = ? AND house_number = ? AND city = ? AND postal_code = ? AND country = ? AND DATE_FORMAT(CONVERT_TZ(creation_time, @@global.time_zone, ?), "%d/%m/%Y %H:%i") = ? AND is_admin = ? AND is_hint_manager = ? AND is_user_manager = ? AND is_authorized_browser_manager = ?';
            $stmt = $conn->prepare($commString);
            $timezone = Database::TIMEZONE;
            $stmt->bind_param('ssssssssssiiiiissssssssssssssiiii', $newUser->email, $newUser->firstName, $newUser->lastName, $newUser->phone, $newUser->dateOfBirth, $newUser->street, $newUser->houseNumber, $newUser->city, $newUser->postalCode, $newUser->country, $newUser->isAdmin, $newUser->isHintManager, $newUser->isUserManager, $newUser->isAuthorizedBrowserManager, $oldUser->userId, $oldUser->email, $oldUser->firstName, $oldUser->lastName, $oldUser->passwordHash, $oldUser->balance, $oldUser->phone, $oldUser->dateOfBirth, $oldUser->street, $oldUser->houseNumber, $oldUser->city, $oldUser->postalCode, $oldUser->country, $timezone, $oldUser->creationTime, $oldUser->isAdmin, $oldUser->isHintManager, $oldUser->isUserManager, $oldUser->isAuthorizedBrowserManager);
            
            if (!$stmt->execute()) {
                if ($stmt->errno == 1062)
                    throw new UserDBException('Cannot update user.', UserDBException::EMAILALREADYEXISTS);
                else
                    throw new UserDBException('Unknown error during statement execution while updating user.', UserDBException::UNKNOWNERROR);
            }
            else if ($stmt->affected_rows == 0)
                throw new UserDBException('The user is out of date, someone else has probably already changed the user.', UserDBException::USEROUTOFDATE);
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

    public static function updatePassword($oldUser, $newPasswordHash) {
        try {
            $conn = Database::getConnection();
            $commString = 'UPDATE stippers_users SET password_hash = ? ' .
                'WHERE user_id = ? AND email = ? AND first_name = ? AND last_name = ? AND password_hash = ? AND balance = ? AND phone = ? AND date_of_birth = STR_TO_DATE(?, "%d/%m/%Y") AND street = ? AND house_number = ? AND city = ? AND postal_code = ? AND country = ? AND DATE_FORMAT(CONVERT_TZ(creation_time, @@global.time_zone, ?), "%d/%m/%Y %H:%i") = ? AND is_admin = ? AND is_hint_manager = ? AND is_user_manager = ? AND is_authorized_browser_manager = ?';
            $stmt = $conn->prepare($commString);
            $timezone = Database::TIMEZONE;
            $stmt->bind_param('sissssssssssssssiiii', $newPasswordHash, $oldUser->userId, $oldUser->email, $oldUser->firstName, $oldUser->lastName, $oldUser->passwordHash, $oldUser->balance, $oldUser->phone, $oldUser->dateOfBirth, $oldUser->street, $oldUser->houseNumber, $oldUser->city, $oldUser->postalCode, $oldUser->country, $timezone, $oldUser->creationTime, $oldUser->isAdmin, $oldUser->isHintManager, $oldUser->isUserManager, $oldUser->isAuthorizedComputerManager);
            
            if (!$stmt->execute())
                throw new UserDBException('Unknown error during statement execution while updating user.', UserDBException::UNKNOWNERROR);
            else if ($stmt->affected_rows == 0)
                throw new UserDBException('The user is out of date, someone else has probably already changed the user.', UserDBException::USEROUTOFDATE);
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

    public static function resetPassword($email, $newPasswordHash) {
        try {
            $conn = Database::getConnection();
            $commString = 'UPDATE stippers_users SET password_hash = ? WHERE email = ?';
            $stmt = $conn->prepare($commString);
            $stmt->bind_param('ss', $newPasswordHash, $email);
            if (!$stmt->execute())
                throw new UserDBException('Unknown error during statement execution while updating user.', UserDBException::UNKNOWNERROR);
            else if ($stmt->affected_rows == 0)
                throw new UserDBException('The user is out of date, someone else has probably already changed the user.', UserDBException::USEROUTOFDATE);
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

    public static function renewMembership($oldUser, $newUser, $cardNumber) {
        try {
            $conn = Database::getConnection();
            $conn->autocommit(false);
            $commString = 'UPDATE stippers_users SET email = ?, first_name = ?, last_name = ?, phone = ?, date_of_birth = STR_TO_DATE(?, "%d/%m/%Y"), street = ?, house_number = ?, city = ?, postal_code = ?, country = ?' .
                'WHERE user_id = ? AND email = ? AND first_name = ? AND last_name = ? AND password_hash = ? AND balance = ? AND phone = ? AND date_of_birth = STR_TO_DATE(?, "%d/%m/%Y") AND street = ? AND house_number = ? AND city = ? AND postal_code = ? AND country = ? AND DATE_FORMAT(CONVERT_TZ(creation_time, @@global.time_zone, ?), "%d/%m/%Y %H:%i") = ? AND is_admin = ? AND is_hint_manager = ? AND is_user_manager = ? AND is_authorized_browser_manager = ?';
            $stmt = $conn->prepare($commString);
            $timezone = Database::TIMEZONE;
            $stmt->bind_param('ssssssssssissssssssssssssiiii', $newUser->email, $newUser->firstName, $newUser->lastName, $newUser->phone, $newUser->dateOfBirth, $newUser->street, $newUser->houseNumber, $newUser->city, $newUser->postalCode, $newUser->country, $oldUser->userId, $oldUser->email, $oldUser->firstName, $oldUser->lastName, $oldUser->passwordHash, $oldUser->balance, $oldUser->phone, $oldUser->dateOfBirth, $oldUser->street, $oldUser->houseNumber, $oldUser->city, $oldUser->postalCode, $oldUser->country, $oldUser->bartenderInfo, $timezone, $oldUser->creationTime, $oldUser->isAdmin, $oldUser->isHintManager, $oldUser->isUserManager, $oldUser->isAuthorizedBrowserManager);
            
            if (!$stmt->execute()) {
                if ($stmt->errno == 1062)
                    throw new UserDBException('Cannot update user.', UserDBException::EMAILALREADYEXISTS);
                else
                    throw new UserDBException('Unknown error during statement execution while updating user.', UserDBException::UNKNOWNERROR);
            }
            else if ($stmt->affected_rows == 0)
                throw new UserDBException('The user is out of date, someone else has probably already changed the user.', UserDBException::USEROUTOFDATE);

            $stmt->close();
            //Inserting 0000 for the year is a dirty hack to work around a bug in MySQL < 5.7.1.
            //Anywhere in the code where the year should go to the default (current year) you should insert 0000 instead of NULL.
            $commString = 'INSERT INTO members_user_card_year (user, card, membership_year) VALUES (?, ?, 0000)';
            $stmt = $conn->prepare($commString);
            $stmt->bind_param('ii', $oldUser->userId, $cardNumber);
            
            if ($stmt->execute())
                $conn->commit();
            else {
                if ($stmt->errno == 1062) {
                    if (substr($stmt->error, strlen($stmt->error) - 9) == '"PRIMARY"')
                        throw new UserDBException('This user is already a member this year.', UserDBException::USERALREADYMEMBER);
                    else
                        throw new UserDBException('This card is already used.', UserDBException::CARDALREADYUSED);
                }
                else
                    throw new UserDBException('Unknown error during statement execution while renewing user.', UserDBException::UNKNOWNERROR);
            }
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

    public static function isUserMemberThisYearByUserId($userId) {
        try {
            $conn = Database::getConnection();
            $commString = 'SELECT count(*) FROM stippers_user_card_year WHERE user = ? AND membership_year = (SELECT YEAR(NOW()))';
            $stmt = $conn->prepare($commString);
            $stmt->bind_param('i', $userId);
            
            if (!$stmt->execute())
                throw new UserDBException('Unknown error during statement execution while checking if the user is a member this year.', CheckInDBException::UNKNOWNERROR);
            else {
                $stmt->bind_result($userCardYear);
                
                if ($stmt->fetch()) {
                    if ($userCardYear == 0)
                        return false;
                    return true;
                }
                else
                    throw new UserDBException('Unknown error during statement execution while checking if the user is a member this year.', CheckInDBException::UNKNOWNERROR);
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
/*
    public static function getUserYearsDetailsByUserId($userId) {
        try {
            $conn = Database::getConnection();
            $commString = 'SELECT membership_year, card, (SELECT count(*) FROM stippers_check_ins WHERE YEAR(time) = membership_year AND user = ?)
            FROM stippers_user_card_year ORDER BY year DESC';
            $stmt = $conn->prepare($commString);
            $stmt->bind_param('i', $userId);
            
            if (!$stmt->execute())
                throw new UserDBException('Unknown error during statement execution while checking if the user is a member this year.', CheckInDBException::UNKNOWNERROR);
            else {
                $stmt->bind_result($membershipYear, $cardNumber, $nCheckIns, $sweater, $sweaterSize, $tshirt, $tshirtSize);

                $userYearsDetails = array();
                $i = 0;
                
                while ($stmt->fetch()) {
                    $userYearsDetails[$i]['membershipYear'] = $membershipYear;
                    $userYearsDetails[$i]['cardNumber'] = $cardNumber;
                    $userYearsDetails[$i]['nCheckIns'] = $nCheckIns;
                    $userYearsDetails[$i]['sweater'] = $sweater;
                    if ($sweater)
                        $userYearsDetails[$i]['sweaterSize'] = $sweaterSize;
                    else
                        $userYearsDetails[$i]['sweaterSize'] = -1;
                    $userYearsDetails[$i]['tshirt'] = $tshirt;
                    if ($tshirt)
                        $userYearsDetails[$i]['tshirtSize'] = $tshirtSize;
                    else
                        $userYearsDetails[$i]['tshirtSize'] = -1;
                    $i++;
                }

                return $userYearsDetails;
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
    */
    public static function getUserIdsThisYear() {
        try {
            $conn = Database::getConnection();
            $commString = 'SELECT user FROM stippers_user_card_year WHERE membership_year = (SELECT YEAR(NOW()))';
            $stmt = $conn->prepare($commString);
            
            if (!$stmt->execute())
                throw new UserDBException('Unknown error during statement execution while getting the user IDs.', UserDBException::UNKNOWNERROR);
            else {
                $stmt->bind_result($userId);

                $userIds = array();
                $i = 0;
                
                while ($stmt->fetch()) {
                    $userIds[$i] = $userId;
                    $i++;
                }

                return $userIds;
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
}