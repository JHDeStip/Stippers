<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Class to send emails. It contains methods to send emails to users.
 */

require_once 'EmailException.php';

require_once __DIR__.'/../../config/EmailConfig.php';

require_once __DIR__.'/../../models/user/User.php';

abstract class Email {
    
    /**
     * Sends an email to users.
     * 
     * @param string $emailFile file name of file which contains the message text (can be html).
     * @param string $subject the subject of the email
     * @param string $fromAddress the address set as the sender
     * @param array $users user objects to send mails to.
     * Each user must at least have userId, firstName, lastName and email set.
     * 
     * @param array $extras extra data to substitute variables in the mail text with.
     * $extras can contain a 'common' index, which contains an array. Each variable in the
     * email text will be substituted by the value of the correspondig key.
     * $extras can also contain arrays on keys that correspond to user IDs. These work similar
     * to the 'common' index, but the values can be unique for each user. For each mail the values
     * from the key for the current user are taken.
     * 
     * @return array array containing email addresses for which sending the email failed.
     * @throws EmailException exception for if somthing went wrong while sending emails.
     */
    public static function sendEmails($emailFile, $subject, $fromAddress, array $users, $extras) {
        $failedAddresses = array();
        
        //Try to read the email file
        if ($emailString = file_get_contents(__DIR__.'/../../../'.EmailConfig::EMAILFILESDIR.'/'.$emailFile)) {
            
            //Set headers
            $headers = 'From: '.$fromAddress.PHP_EOL
                .'MIME-Version: 1.0'.PHP_EOL
                .'Content-Type: text/html; charset=utf-8'.PHP_EOL;
        
            foreach ($users as $user) {
                
                //Set search and replace for firstName and lastName
                $search = array('%firstName%', '%lastName%');
                $replace = array(htmlentities($user->firstName, ENT_QUOTES, 'utf-8'), htmlentities($user->lastName, ENT_QUOTES, 'utf-8'));
                
                //Set search and replace for common extras. Also add % signs to the search strings
                if (isset($extras['common']) && is_array($extras['common'])) {
                    $search = array_merge($search, array_map(function($key){ return '%'.$key.'%'; }, array_keys($extras['common'])));
                    $replace = array_merge($replace, array_map(function($val){ return htmlentities($val, ENT_QUOTES, 'utf-8'); }, $extras['common']));
                }
                
                //Set search and replace for user specific extras. Also add % signs to the search strings
                if (isset($extras[$user->userId]) && is_array($extras[$user->userId])) {
                    $search = array_merge($search, array_map(function($key){ return '%'.$key.'%'; }, array_keys($extras[$user->userId])));
                    $replace = array_merge($replace, array_map(function($val){ return htmlentities($val, ENT_QUOTES, 'utf-8'); }, $extras[$user->userId]));
                }
                
                //Build message
                $message = str_replace($search, $replace, $emailString);
                
                //Send email, if fail add email to failedAddresses
                if (!mail($user->email, htmlentities($subject, ENT_QUOTES, 'utf-8'), $message, $headers))
                    array_push($failedAddresses, $user->email);
                
            }
            
            return $failedAddresses;
        }
        else
            throw new EmailException('Cannot read the email file.', EmailException::CANNOTREADEMAILFILE);
    }
}