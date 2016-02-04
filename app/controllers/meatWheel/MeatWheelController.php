<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Controller for the meat wheel page.
 */

require_once __DIR__.'/../IController.php';
require_once __DIR__.'/../../helperClasses/Page.php';

abstract class MeatWheelController implements IController {
    
    public static function get() {
        $page = new Page();
        $page->data['title'] = 'Het vleeswiel';
        
        $page->addView('meatWheel/MeatWheelView');
        $page->addExtraJsFile('konva/konva.min.js');
        
        $page->showWithMenu();
    }
    
    public static function post() {
        MeatWheelController::get();
    }
}