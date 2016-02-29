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

require_once __DIR__.'/../../models/chat/ChatMessage.php';
require_once __DIR__.'/../../models/chat/ChatDB.php';
require_once __DIR__.'/../../models/chat/ChatDBException.php';

require_once __DIR__.'/../../views/chat/ChatViewValidator.php';

abstract class ChatController implements IController {
    
    public static function get() {
        $page = new Page();
        $page->data['title'] = 'Chat';
        
        ChatController::buildChatView($page, false);
        
        $page->addView('Chat/ChatView');
        $page->addExtraJsFile('views/chat/ChatViewMessageListRefresher.js');
        $page->addExtraJsFile('views/chat/chatViewOnLoadHandler.js');
        
        $page->showWithMenu();
    } 
    
    public static function post() {
        $page = new Page();
        $page->data['title'] = 'Chat';
        
        $page->addView('Chat/ChatView');
        $page->addExtraJsFile('views/chat/ChatViewMessageListRefresher.js');
        $page->addExtraJsFile('views/chat/chatViewOnLoadHandler.js');
        
        $errMsgs = ChatViewValidator::validate($_POST);
        if (empty($errMsgs)) {
            try {
                ChatDB::addChatMessage(new ChatMessage(null, null, null, $_SESSION['Stippers']['user']->userId, $_POST['new_message'], null));
                ChatController::buildChatView($page, false);
            }
            catch (Exception $ex) {
                ChatController::buildChatView($page, true);
                $page->data['ChatView']['errMsgs']['global'] = '<h2 class="error_message" id="new_message_form_error_message">Kan bericht niet posten, probeer het opnieuw.</h2>';
            }
        }
        else {
            ChatController::buildChatView($page, true);
            $page->data['ChatView']['errMsgs'] = array_merge($page->data['ChatView']['errMsgs'], $errMsgs);
        }
        
        $page->showWithMenu();
    }
    
    /**
     * Builds the view to enter a new chat message
     * that are not permission related.
     * 
     * @param Page $page page object to load data into
     * @param type $saveMode indicates if we are trying to safe
     */
    private static function buildChatView($page, $saveMode) {
        $page->data['ChatView']['new_message_formAction'] = $_SERVER['REQUEST_URI'];
        
        //If we're traying to save we read the data from post
        if ($saveMode)
            $page->data['ChatView']['newMessage'] = $_POST['new_message'];
        else
            $page->data['ChatView']['newMessage'] = '';
        
        $page->data['ChatView']['errMsgs'] = ChatViewValidator::initErrMsgs();
    }
}