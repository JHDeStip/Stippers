<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Model representing a chat message.
 */

class ChatMessage {
    public $messageId;
    public $posterFirstName;
    public $posterLastName;
    public $user;
    public $text;
    public $messageTime;

    public function __construct($messageId, $posterFirstName, $posterLastName, $user, $text, $messageTime)
    {
        $this->messageId = $messageId;
        $this->posterFirstName = $posterFirstName;
        $this->posterLastName = $posterLastName;
        $this->user = $user;
        $this->text = $text;
        $this->messageTime = $messageTime;
    }
}