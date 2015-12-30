<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Controller for the manage email page.
 */

require_once __DIR__.'/../IController.php';
require_once __DIR__.'/../../helperClasses/Page.php';

require_once __DIR__.'/../../config/EmailConfig.php';

require_once __DIR__.'/../../views/manageEmail/EmailUploadViewValidator.php';

abstract class ManageEmailController implements IController {
    
    public static function get() {
        $page = new Page();
        $page->data['title'] = 'E-mails beheren';
        $page->addView('manageEmail/ManageEmailTitleView');
        
        ManageEmailController::buildEmailListView($page);
        ManageEmailController::buildEmailUploadView($page);
        
        $page->showWithMenu();
    }
    
    public static function post() {
        $page = new Page();
        $page->data['title'] = 'E-mails beheren';
        
        
        $errMsgs = EmailUploadViewValidator::validate($_FILES);
        
        if (empty($errMsgs)) {
            //If the file already exists we show the error
            if (file_exists(EmailConfig::EMAILFILESDIR.'/'.$_FILES['email_file']['name'])) {
                $page->addView('manageEmail/ManageEmailTitleView');
                ManageEmailController::buildEmailListView($page);
                ManageEmailController::buildEmailUploadView($page);
                $page->data['EmailUploadView']['errMsgs']['global'] = '<h2 class="error_message" id="email_upload_form_error_info_message">Een bestand met deze naam bestaat al.</h2>';
            }
            //Try to move the file from temp location to desired directory and show success message
            elseif (move_uploaded_file($_FILES['email_file']['tmp_name'], EmailConfig::EMAILFILESDIR.'/'.$_FILES['email_file']['name'])) {
                $page->data['SuccessMessageNoDescriptionWithLinkView']['successTitle'] = 'Bestand succesvol ge&uuml;pload.';
                $page->data['SuccessMessageNoDescriptionWithLinkView']['redirectUrl'] = $_SERVER['REQUEST_URI'];
                $page->addView('success/SuccessMessageNoDescriptionWithLinkView');
            }
            //If move failed we show an error
            else {
                $page->addView('manageEmail/ManageEmailTitleView');
                ManageEmailController::buildEmailListView($page);
                ManageEmailController::buildEmailUploadView($page);
                $page->data['EmailUploadView']['errMsgs']['global'] = '<h2 class="error_message" id="email_upload_form_error_info_message">Uploaden mislukt, probeer opnieuw.</h2>';
            }
        }
        //Show error
        else {
            $page->addView('manageEmail/ManageEmailTitleView');
            ManageEmailController::buildEmailListView($page);
            ManageEmailController::buildEmailUploadView($page);
            $page->data['EmailUploadView']['errMsgs'] = array_merge($page->data['EmailUploadView']['errMsgs'], $errMsgs);
        }
        
        $page->showWithMenu();
    }
    
    /**
     * Builds the view to show the available email list
     * 
     * @param Page $page page to load the view into
     */
    private static function buildEmailListView(Page $page) {
        //Check if our directory is there and there are emails
        if (is_dir(EmailConfig::EMAILFILESDIR)) {
            $fileNames =  array_slice(scandir(EmailConfig::EMAILFILESDIR), 2);
            //Remove .htaccess from the list (this is there so 'the outer world' cannot download files from this directory
            unset($fileNames[array_search('.htaccess', $fileNames)]);
            $fileNames = array_values($fileNames);
            
            if (count($fileNames) > 0) {
                //If there are emails we show them
                $page->data['ManageEmailEmailListView']['fileNames'] = $fileNames;
                $page->addView('manageEmail/ManageEmailEmailListView');
            }
            else
                //No emails -> snow no emails view
                $page->addView('manageEmail/ManageEmailNoEmailsView');
        }
        else
            //No emails -> snow no emails view
            $page->addView('manageEmail/ManageEmailNoEmailsView');
    }
    
    /**
     * Builds the view to upload an email.
     * 
     * @param Page $page page to load the view into
     */
    private static function buildEmailUploadView(Page $page) {
        $page->data['EmailUploadView']['errMsgs'] = EmailUploadViewValidator::initErrMsgs();
        $page->data['EmailUploadView']['email_upload_formAction'] = $_SERVER['REQUEST_URI'];
        $page->addView('manageEmail/EmailUploadView');
    }
    
}