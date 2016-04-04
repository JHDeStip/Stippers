<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Class to do database operations regarding browser models.
 */

require_once __DIR__.'/../../helperClasses/database/Database.php';
include_once 'ChatMessage.php';
include_once 'ChatDBException.php';

abstract class ChatDB {
    
    /**
     * Adds a new chat message.
     * 
     * @param ChatMessage $message message to add to the database
     * @throws Exception generic error for if something goes wrong while talking to the database
     * @throws ChatDBException error for if something goes wrong while adding the message
     */    
    public static function addChatMessage($message) {
        try {
            $conn = Database::getConnection();

            $commString = 'INSERT INTO stippers_chat_messages (user, text) VALUES (?, ?)';
            $stmt = $conn->prepare($commString);
            
            //Check if statement could be prepared
            if ($stmt) {
                $stmt->bind_param('is', $message->user, $message->text);
            
                if (!$stmt->execute())
                    throw new ChatDBException("Unknown error during statement execution while adding chat message.", ChatDBException::UNKNOWNERROR);
            }
            else
                throw new ChatDBException('Cannot prepare statement.', ChatDBException::CANNOTPREPARESTMT);
        }
        catch (Exception $ex)
        {
            throw $ex;
        }
        finally {
            if (isset($conn)){
                $conn->kill($conn->thread_id);
                $conn->close();
            }
        }
    }

    
    /**
     * Gets all chat messages.
     * 
     * @param int $limit the max amount of messages to return
     * @return array Browser
     * @throws Exception generic error for if something goes wrong while talking to the database
     * @throws BrowserDBException error for if something goes wrong while getting the browsers
     */
    public static function getChatMessages($limit) {
        try{
            $conn = Database::getConnection();
            $commString = 'SELECT message_id, first_name, last_name, user, text, DATE_FORMAT(CONVERT_TZ(message_time, @@global.time_zone, ?), "%d/%m/%Y %H:%i") message_time FROM stippers_chat_messages JOIN stippers_users ON user = user_id ORDER BY message_id DESC LIMIT ?';
            $stmt = $conn->prepare($commString);
            
            //Check if statement could be prepared
            if ($stmt) {
                
                $timezone = GlobalConfig::MYSQL_TIME_ZONE;
                $stmt->bind_param('si', $timezone, $limit);
                if (!$stmt->execute())
                    throw new ChatDBException('Unknown error during statement execution while getting chat messages.', ChatDBException::UNKNOWNERROR);
                else {
                    $stmt->bind_result($messageId, $firstName, $lastName, $user, $text, $messageTime);
                    $messagesUserNames = array();
                    
                    while ($stmt->fetch())
                        array_push($messagesUserNames, new ChatMessage($messageId, $firstName, $lastName, $user, $text, $messageTime));    
    
                    return $messagesUserNames;
                }
            }
            else
                throw new ChatDBException('Cannot prepare statement.', ChatDBException::CANNOTPREPARESTMT);
        }
        catch (Exception $ex)
        {
            throw $ex;
        }
        finally {
            if (isset($conn)){
                $conn->kill($conn->thread_id);
                $conn->close();
            }
        }
    }
}