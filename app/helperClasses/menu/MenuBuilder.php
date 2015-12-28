<?php

require_once __DIR__.'/../Page.php';

abstract class MenuBuilder {
    public static function buildMenu(Page $page) {
        $page->data['MenuBarView']['showLogoutLink'] = false;
        $page->data['MenuBarView']['showLoginLink'] = false;
        $page->data['MenuBarView']['showManagementLink'] = false;
        $page->data['MenuBarView']['showAddRenewUsersLink'] = false;
        $page->data['MenuBarView']['showCheckInLink'] = false;
        $page->data['MenuBarView']['showUserManagementLink'] = false;
        $page->data['MenuBarView']['showWeeklyWinnerLink'] = false;
        $page->data['MenuBarView']['showBrowserManagerLink'] = false;
        
        if (isset($_SESSION['Stippers']['user'])) {
            $page->data['MenuBarView']['showLogoutLink'] = true;
            if ($_SESSION['Stippers']['user']->isAdmin || $_SESSION['Stippers']['user']->isUserManager || $_SESSION['Stippers']['user']->isBrowserManager)
                $page->data['MenuBarView']['showManagementLink'] = true;
            
            if ($_SESSION['Stippers']['user']->isAdmin || $_SESSION['Stippers']['user']->isUserManager) {
                $page->data['MenuBarView']['showUserManagementLink'] = true;
                $page->data['MenuBarView']['showWeeklyWinnerLink'] = true;
            }
            if ($_SESSION['Stippers']['user']->isAdmin || $_SESSION['Stippers']['user']->isBrowserManager)
                $page->data['MenuBarView']['showBrowserManagementLink'] = true;
            
        }
        else
            $page->data['MenuBarView']['showLoginLink'] = true;
        
        if (isset($_SESSION['Stippers']['browser'])) {
            if ($_SESSION['Stippers']['browser']->canAddRenewUsers)
                $page->data['MenuBarView']['showAddRenewUsersLink'] = true;
            if ($_SESSION['Stippers']['browser']->canCheckIn)
                $page->data['MenuBarView']['showCheckInLink'] = true;
        }
    }
}