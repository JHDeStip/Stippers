<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Controller for the download search results feature.
 */

require_once __DIR__.'/../IController.php';

require_once __DIR__.'/../../helperClasses/Page.php';

require_once __DIR__.'/../../config/DownloadSearchResultsConfig.php';

require_once __DIR__.'/../../models/user/User.php';
require_once __DIR__.'/../../models/user/UserDB.php';
require_once __DIR__.'/../../models/user/UserDBException.php';

abstract class DownloadSearchResultsController implements IController {
    
    public static function get() {
        try {
            //Get search results
            $searchUsers = UserDB::getSearchUsers($_SESSION['Stippers']['ManageUserSearch']['inputData']['show'], $_SESSION['Stippers']['ManageUserSearch']['inputData']['values'], $_SESSION['Stippers']['ManageUserSearch']['inputData']['options']);
            
            $csvString = '';
            //If there are results we build the csv string
            if (count($searchUsers) > 0) {
                //Create headers
                $csvString .= '#';
                if (isset($searchUsers[0]['user']->lastName)) $csvString .= ';Achternaam';
                if (isset($searchUsers[0]['user']->firstName)) $csvString .= ';Voornaam';
                if (isset($searchUsers[0]['membershipYear'])) $csvString .= ';Lidjaar';
                if (isset($searchUsers[0]['cardNumber'])) $csvString .= ';Kaartnummer';
                if (isset($searchUsers[0]['user']->street)) $csvString .= ';Straat';
                if (isset($searchUsers[0]['user']->houseNumber)) $csvString .= ';Huisnummer';
                if (isset($searchUsers[0]['user']->city)) $csvString .= ';Gemeente';
                if (isset($searchUsers[0]['user']->postalCode)) $csvString .= ';Postcode';
                if (isset($searchUsers[0]['user']->email)) $csvString .= ';E-mail';
                if (isset($searchUsers[0]['user']->dateOfBirth)) $csvString .= ';Geboortedatum';
                if (isset($searchUsers[0]['user']->balance)) $csvString .= ';Saldo';
                if (isset($searchUsers[0]['user']->creationTime)) $csvString .= ';Registratietijd';
                if (isset($searchUsers[0]['nCheckIns'])) $csvString .= ';Aantal check-ins';
                if (isset($searchUsers[0]['user']->isAdmin)) $csvString .= ';Administrator';
                if (isset($searchUsers[0]['user']->isUserManager)) $csvString .= ';Gebruikersbeheerder';
                if (isset($searchUsers[0]['user']->isBrowserManager)) $csvString .= ';Browserbeheerder';
                if (isset($searchUsers[0]['user']->isMoneyManager)) $csvString .= ';Geldbeheerder';
                
                //Add data rows
                for ($i=0; $i<count($searchUsers); $i++) {
                    $csvString .= PHP_EOL.$i;
                    if (isset($searchUsers[$i]['user']->lastName)) $csvString .= ';'.$searchUsers[$i]['user']->lastName;
                    if (isset($searchUsers[$i]['user']->firstName)) $csvString .= ';'.$searchUsers[$i]['user']->firstName;
                    if (isset($searchUsers[$i]['membershipYear'])) $csvString .= ';'.$searchUsers[$i]['membershipYear'];
                    if (isset($searchUsers[$i]['cardNumber'])) $csvString .= ';'.$searchUsers[$i]['cardNumber'];
                    if (isset($searchUsers[$i]['user']->street)) $csvString .= ';Straat';
                    if (isset($searchUsers[$i]['user']->houseNumber)) $csvString .= ';'.$searchUsers[$i]['user']->houseNumber;
                    if (isset($searchUsers[$i]['user']->city)) $csvString .= ';'.$searchUsers[$i]['user']->city;
                    if (isset($searchUsers[$i]['user']->postalCode)) $csvString .= ';'.$searchUsers[$i]['user']->postalCode;
                    if (isset($searchUsers[$i]['user']->email)) $csvString .= ';'.$searchUsers[$i]['user']->email;
                    if (isset($searchUsers[$i]['user']->dateOfBirth)) $csvString .= ';'.$searchUsers[$i]['user']->dateOfBirth;
                    if (isset($searchUsers[$i]['user']->balance)) $csvString .= ';'.$searchUsers[$i]['user']->balance;
                    if (isset($searchUsers[$i]['user']->creationTime)) $csvString .= ';'.$searchUsers[$i]['user']->creationTime;
                    if (isset($searchUsers[$i]['nCheckIns'])) $csvString .= ';'.$searchUsers[$i]['nCheckIns'];
                    if (isset($searchUsers[$i]['user']->isAdmin)) $csvString .= ';'.($searchUsers[$i]['user']->isAdmin ? 'Ja' : 'Nee');
                    if (isset($searchUsers[$i]['user']->isUserManager)) $csvString .= ';'.$searchUsers[$i]['user']->isUserManager;
                    if (isset($searchUsers[$i]['user']->isBrowserManager)) $csvString .= ';'.$searchUsers[$i]['user']->isBrowserManager;
                    if (isset($searchUsers[$i]['user']->isMoneyManager)) $csvString .= ';'.$searchUsers[$i]['user']->isMoneyManager;
                }
            }
            
            //Send header to browser so it will download the data as file instead of showing it
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename='.DownloadSearchResultsConfig::FILE_NAME);
            
            //Write to php output stream (the browser will save this as file
            $output = fopen('php://output', 'w');
            fwrite($output, $csvString);
            fclose($output);
        }
        catch (Exception $ex) {
            //If something goes wrong we show the error page.
            $page = new Page();
            $page->data['ErrorMessageNoDescriptionWithLinkView']['errorTitle'] = 'Kan download voor zoekresultaten niet genereren';
            $page->data['ErrorMessageNoDescriptionWithLinkView']['tryAgainUrl'] = $_SERVER['REQUEST_URI'];
            $page->addView('error/ErrorMessageNoDescriptionWithLinkView');
            $page->showWithMenu();
        }
    }
    
    public static function post() {
        DownloadSearchResultsController::get();
    }
}