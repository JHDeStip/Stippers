<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Controller for the edit email page.
 */

require_once __DIR__.'/../IController.php';
require_once __DIR__.'/../../helperClasses/Page.php';

require_once __DIR__.'/../../config/EmailConfig.php';

abstract class EditEmailController implements IController {
    
    public static function get() {
        
        if (!isset($_GET['filename']) || !file_exists(EmailConfig::EMAILFILESDIR.'/'.$_GET['filename']))
            header('Location: manageemail', true, 303);
        else {
            $page = new Page();
            $page->data['title'] = 'E-mail bewerken';
            
            $page->data['EditEmailView']['errMsgs']['global'] = '';
            $page->data['EditEmailView']['edit_email_formAction'] = $_SERVER['REQUEST_URI'];
            $page->data['EditEmailView']['fileName'] = $_GET['filename'];
            $page->addView('manageEmail/EditEmailView');
            
            $page->showWithMenu();
        }
        
    }
    
    public static function post() {
        //If the file in get doesn't exist redirect to manageemail page
        if (!isset($_GET['filename']) || !file_exists(EmailConfig::EMAILFILESDIR.'/'.$_GET['filename']))
            header('Location: manageemail', true, 303);
        //If download is clicked, set headers and download file
        elseif (isset($_POST['download'])) {
            header('Content-Type: application/octet-stream');
            header('Content-Transfer-Encoding: Binary');
            header('Content-disposition: attachment; filename='.$_GET['filename']);
            readfile(EmailConfig::EMAILFILESDIR.'/'.$_GET['filename']);
        }
        //If delete is clicked try to unlink file and show success message.
        elseif (isset($_POST['delete'])) {
            unlink(EmailConfig::EMAILFILESDIR.'/'.$_GET['filename']);
            
            $page = new Page();
            $page->data['title'] = 'E-mail bewerken';
            $page->data['SuccessMessageNoDescriptionWithLinkView']['successTitle'] = 'E-mail succesvol verwijderd';
            $page->data['SuccessMessageNoDescriptionWithLinkView']['redirectUrl'] = 'manageemail';
            
            $page->addView('success/SuccessMessageNoDescriptionWithLinkView');
            $page->showWithMenu();
        }
        else
            header('Location: manageemail', true, 303);
    }
    
}