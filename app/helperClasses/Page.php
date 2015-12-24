<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * This class holds the data required to show a page and provides a method to show the page.
 */

class Page {
    public $views = array();
    public $data = null;
        
    /**
     * Shows a page with the views that have been previously given.
     * Views will be displayed in the order they were added.
     */
    public function showBasic() {
        require_once __DIR__.'/../views/common/Header.html' ;
        foreach ($this->views as $view)
            require_once __DIR__.'/../views/'.$view.'.html';
        require_once __DIR__.'/../views/common/Footer.html';
    }
    
    /**
     * Adds a view that will be shown when the page is displayed.
     * 
     * @param string $viewName name of the view to add
     */
    public function addView($viewName) {
        array_push($this->views, $viewName);
    }
}
