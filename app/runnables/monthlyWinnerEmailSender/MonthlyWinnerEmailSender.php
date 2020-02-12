<?php

require_once __DIR__.'/../IRunnable.php';

require_once __DIR__.'/../../config/GlobalConfig.php';

require_once __DIR__.'/../../helperClasses/email/Email.php';

require_once __DIR__.'/../../models/user/User.php';
require_once __DIR__.'/../../models/user/UserDB.php';

require_once __DIR__.'/../../models/checkIn/CheckInDB.php';

abstract class MonthlyWinnerEmailSender implements IRunnable {
    private const EMAIL_SUBJECT = 'Lid van de maand';
    private const USER_MANAGER_EMAIL_FILE = 'MonthlyWinnerNotification.html';
    private const WINNER_EMAIL_FILE = 'MonthlyWinnerCongratulations.html';

    public static function run() {
        try {
            $select = array('email' => true);
            $searchFilter = array('isUserManager' => true);
            $userManagerEmailUsers = array_column(UserDB::getSearchUsers($select, $searchFilter, null), 'user');

            $startOfLastMonth = strtotime('midnight first day of last month '.GlobalConfig::PHP_TIME_ZONE);
            $startOfThisMonth = strtotime('midnight first day of this month '.GlobalConfig::PHP_TIME_ZONE);
            
            $userId = CheckInDB::getMostCheckedInNonUserManagerUserIdBetween($startOfLastMonth, $startOfThisMonth);

            if ($userId == null) {
                $emailValues['common']['winnerFirstName'] ='GEEN';
                $emailValues['common']['winnerLastName'] = 'WINNAAR';

                Email::sendEmails(MonthlyWinnerEmailSender::USER_MANAGER_EMAIL_FILE, MonthlyWinnerEmailSender::EMAIL_SUBJECT, EmailConfig::FROM_ADDRESS, $userManagerEmailUsers, $emailValues);
                                
                return;
            }

            $user = UserDB::getBasicUserById($userId);

            $emailValues['common']['winnerFirstName'] = $user->firstName;
            $emailValues['common']['winnerLastName'] = $user->lastName;

            Email::sendEmails(MonthlyWinnerEmailSender::USER_MANAGER_EMAIL_FILE, MonthlyWinnerEmailSender::EMAIL_SUBJECT, EmailConfig::FROM_ADDRESS, $userManagerEmailUsers, $emailValues);
            Email::sendEmails(MonthlyWinnerEmailSender::WINNER_EMAIL_FILE, MonthlyWinnerEmailSender::EMAIL_SUBJECT, EmailConfig::FROM_ADDRESS, array($user), $emailValues);
        }
        catch (Exception $ex) {
            var_dump($ex);
        }
    }
}