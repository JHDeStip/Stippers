<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Controller for the reset password page.
 */

require_once __DIR__.'/../IController.php';
require_once __DIR__.'/../../helperClasses/Page.php';

require_once __DIR__.'/../../config/SecurityConfig.php';
require_once __DIR__.'/../../config/EmailConfig.php';

require_once __DIR__.'/../../helperClasses/random/Random.php';

require_once __DIR__.'/../../helperClasses/email/Email.php';
require_once __DIR__.'/../../helperClasses/email/EmailException.php';

require_once __DIR__.'/../../models/user/User.php';
require_once __DIR__.'/../../models/user/UserDB.php';
require_once __DIR__.'/../../models/user/UserDBException.php';

require_once __DIR__.'/../../views/sendEmailToUsers/SendEmailToUsersViewValidator.php';

abstract class SendEmailToUsersController implements IController {
    
    public static function get() {
        //If required data is not in session go to search page
        if (!isset($_GET['users']))
            header('Location: manageuser', TRUE, 303);
        else {
            $page = new Page();
            $page->data['title'] = 'E-mail versturen naar gebruiker(s)';
            $page->addView('sendEmailToUsers/SendEmailToUsersTitleView');
            
            SendEmailToUsersController::buildSendEmailToUsersFormView($page, false);
    
            $page->showWithMenu();
        }
    }
    
    public static function post() {
        //If required data is not in session go to search page
        if (!isset($_GET['users']))
            header('Location: manageuser', TRUE, 303);
        else {
            $page = new Page();
            $page->data['title'] = 'E-mail versturen naar gebruiker(s)';
            
            $errMsgs = SendEmailToUsersViewValidator::validate($_POST);
            
            if (empty($errMsgs)) {
                try {
                    //Get users
                    $users = UserDB::getBasicUsersById(explode(',', $_GET['users']));
                    
                    //Send email
                    $failedAddresses = Email::sendEmails($_POST['email_file'], $_POST['subject'], EmailConfig::FROM_ADDRESS, $users, null);

                    //Check if some emails failed
                    if (empty($failedAddresses)) {
                        $page->data['SuccessMessageNoDescriptionWithLinkView']['successTitle'] = 'E-mail(s) succesvol verzonden';
                        $page->data['SuccessMessageNoDescriptionWithLinkView']['redirectUrl'] = 'manageuser';
                        $page->addView('success/SuccessMessageNoDescriptionWithLinkView');
                    }
                    else {
                        $page->data['ErrorMessageWithDescriptionNoLinkView']['errorTitle'] = 'Kan niet alle e-mails verzenden';
                        $page->data['ErrorMessageWithDescriptionNoLinkView']['errorDescription'] = 'Het verzenden van de e-mail naar onderstaande addressen is mislukt.';
                        $page->addView('error/ErrorMessageWithDescriptionNoLinkView');
                        
                        $page->data['FailedEmailListView']['addresses'] = $failedAddresses;
                        $page->data['FailedEmailListView']['redirectUrl'] = 'manageuser';
                        $page->addView('sendEmailToUsers/FailedEmailListView');
                    }
                }
                catch (UserDBException $ex) {
                    $page->data['ErrorMessageNoDescriptionWithLinkView']['errorTitle'] = 'Kan gebruiker(s) niet ophalen';
                    $page->data['ErrorMessageNoDescriptionWithLinkView']['tryAgainUrl'] = $_SERVER['REQUEST_URI'];
                    $page->addView('error/ErrorMessageNoDescriptionWithLinkView');
                }
                catch (EmailException $ex) {
                    if ($ex->getCode() == EmailException::CANNOTREADEMAILFILE)
                        $page->data['ErrorMessageNoDescriptionWithLinkView']['errorTitle'] = 'Kan e-mailbestand niet lezen';
                    else
                        $page->data['ErrorMessageNoDescriptionWithLinkView']['errorTitle'] = 'Kan e-mail(s) niet verzenden';
                    
                    $page->data['ErrorMessageNoDescriptionWithLinkView']['tryAgainUrl'] = $_SERVER['REQUEST_URI'];
                    $page->addView('error/ErrorMessageNoDescriptionWithLinkView');
                }
                catch (Exception $ex) {
                    $page->data['ErrorMessageNoDescriptionWithLinkView']['errorTitle'] = 'Kan e-mail(s) niet verzenden';
                    $page->data['ErrorMessageNoDescriptionWithLinkView']['tryAgainUrl'] = $_SERVER['REQUEST_URI'];
                    $page->addView('error/ErrorMessageNoDescriptionWithLinkView');
                }
            }
            else {
                $page->addView('sendEmailToUsers/SendEmailToUsersTitleView');
                SendEmailToUsersController::buildSendEmailToUsersFormView($page, true);
                $page->data['SendEmailToUsersFormView']['errMsgs'] = array_merge($page->data['SendEmailToUsersFormView']['errMsgs'], $errMsgs);
            }
            
            $page->showWithMenu();
        }
    }

    /**
     * Builds the view to send an email.
     * 
     * @param Page $page page to load the view into
     */
    private static function buildSendEmailToUsersFormView(Page $page, $saveMode) {
        
        //Check if our directory is there and there are emails
        if (is_dir(EmailConfig::EMAILFILESDIR)) {
            $fileNames =  array_slice(scandir(EmailConfig::EMAILFILESDIR), 2);
            //Remove .htaccess from the list (this is there so 'the outer world' cannot download files from this directory
            unset($fileNames[array_search('.htaccess', $fileNames)]);
            $fileNames = array_values($fileNames);
            
            if (count($fileNames) > 0) {
                //If there are emails we show them
                $page->data['SendEmailToUsersFormView']['send_email_to_users_formAction'] = $_SERVER['REQUEST_URI'];
                
                if ($saveMode)
                    $page->data['SendEmailToUsersFormView']['subject'] = $_POST['subject'];
                else
                    $page->data['SendEmailToUsersFormView']['subject'] = '';
                
                $page->data['SendEmailToUsersFormView']['emailFiles'] = $fileNames;
                $page->data['SendEmailToUsersFormView']['errMsgs'] = SendEmailToUsersViewValidator::initErrMsgs();
                $page->addView('sendEmailToUsers/SendEmailToUsersFormView');
            }
            else {
                //No emails -> snow no emails view
                $page->data['NoEmailFilesView']['redirectUrl'] = 'manageemail';
                $page->addView('sendEmailToUsers/NoEmailFilesView');
            }
        }
        else {
            //No emails -> snow no emails view
            $page->data['NoEmailFilesView']['redirectUrl'] = 'manageemail';
            $page->addView('sendEmailToUsers/NoEmailFilesView');
        }
    }
}
