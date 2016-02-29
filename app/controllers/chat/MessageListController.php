<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Controller for the chat page.
 */

require_once __DIR__.'/../IController.php';
require_once __DIR__.'/../../helperClasses/Page.php';

require_once __DIR__.'/../../config/GlobalConfig.php';
require_once __DIR__.'/../../config/ChatConfig.php';

require_once __DIR__.'/../../models/chat/ChatMessage.php';
require_once __DIR__.'/../../models/chat/ChatDB.php';
require_once __DIR__.'/../../models/chat/ChatDBException.php';

abstract class MessageListController implements IController {
    
    public static function get() {
       //Gets the amount from GET or use default
        if (isset($_GET['amount']))
            $amount = $_GET['amount'];
        else
            $amount = ChatConfig::DEFAULT_AMOUNT;
        
        try {
            //Get messages
            $messages = ChatDB::getChatMessages($amount);
            
            foreach ($messages as $message) {
                //Calculate difference between now and message post time
                $timeDiff = (new DateTime(null, new DateTimeZone(GlobalConfig::PHP_TIME_ZONE)))->diff(DateTime::createFromFormat('d/m/Y H:i', $message->messageTime));
                
                //If message was posted less than 1 day ago show time as hour, else show as date
                if ($timeDiff->y > 0 || $timeDiff->m > 0 || $timeDiff->d >= 1)
                    $messageTime = substr($message->messageTime, 0, 10);
                else
                    $messageTime = substr($message->messageTime, 11, 5);
                
                //Cut user's name if it's too long
                $posterFullName = $message->posterFirstName.' '.$message->posterLastName;
                if (strlen($posterFullName) > 10)
                    $posterFullName = substr($posterFullName, 0, 10).'...';
                
                echo '<tr><td>'.htmlentities($posterFullName, ENT_QUOTES, 'utf-8').'</td><td>'.htmlentities($message->text, ENT_QUOTES, 'utf-8').'</td><td>'.htmlentities($messageTime, ENT_QUOTES, 'utf-8').'</td></tr>';
            }
        }
        catch (Exception $ex) {
            http_response_code(500);
        }
    } 
    
    public static function post() {
        //We don't have post so we just call get.
        ChatController::get();
    }
    
}