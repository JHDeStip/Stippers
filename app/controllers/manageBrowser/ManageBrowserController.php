<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Controller for the manage browser page.
 */

require_once __DIR__.'/../IController.php';
require_once __DIR__.'/../../helperClasses/Page.php';

require_once __DIR__.'/../../models/browser/Browser.php';
require_once __DIR__.'/../../models/browser/BrowserDB.php';
require_once __DIR__.'/../../models/browser/BrowserDBException.php';

abstract class ManageBrowserController implements IController {
    
    public static function get() {
        $page = new Page();
        $page->data['title'] = 'Browsers beheren';
        $page->addView('manageBrowser/ManageBrowserTopView');
        
        try {
            //Get list of existing browsers to show
            $browsers = BrowserDB::getBrowsers();
            if (count($browsers) > 0) {
                $page->data['ManageBrowserBrowserListView']['browsers'] = $browsers;
                $page->addView('manageBrowser/ManageBrowserBrowserListView');
            }
            else
                $page->addView('manageBrowser/ManageBrowserNoBrowsersView');
        }
        catch(Exception $ex) {
            $page->data['ErrorMessageNoDescriptionWithLinkView']['errorTitle'] = 'Kan browsers niet ophalen';
            $page->data['ErrorMessageNoDescriptionWithLinkView']['tryAgainUrl'] = $_SERVER['REQUEST_URI'];
            $page->addView('error/ErrorMessageNoDescriptionWithLinkView');
        }
        $page->data['ManageBrowserNewBrowserView']['new_browser_formAction'] = $_SERVER['REQUEST_URI'];
        $page->addView('manageBrowser/ManageBrowserNewBrowserView');
        
        $page->showBasic();
    }
    
    public static function post() {
        if (isset($_POST['add_new_browser']))
            header('Location: addbrowser', TRUE, 303);
    }
}