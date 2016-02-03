<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * This class holds the data required to show a page and provides a method to show the page.
 */

require_once 'menu/MenuBuilder.php';

class Page {
    private $views = array();
    public $data;
    
    function __construct() {
       $this->data['viewJsFiles'] = array();
       $this->data['extraJsFiles'] = array();
    }

        
    /**
     * Shows a page with the views that have been previously given.
     * Views will be displayed in the order they were added.
     */
    public function showBasic() {
        require_once __DIR__.'/../views/common/Header.html';
        foreach ($this->views as $view)
            require_once __DIR__.'/../views/'.$view.'.html';
        require_once __DIR__.'/../views/common/Footer.html';
    }
    
    /**
     * Shows a page with the views that have been previously given.
     * Views will be displayed in the order they were added.
     */
    public function showWithMenu() {
        MenuBuilder::buildMenu($this);
        array_unshift($this->views, 'menu/MenuBarView');
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
        //Add view to list
        array_push($this->views, $viewName);
        //Add javascript file to list
        if (file_exists(__DIR__.'/../js/views/'.$viewName.'.js'))
                array_push($this->data['viewJsFiles'], $viewName);
    }
    
    /**
     * Adds a javascript file to the page.
     * 
     * @param string $jsFile name of the javascript file relative to the /app/js directory.
     */
    public function addExtraJsFile($jsFile) {
        if (file_exists(__DIR__.'/../js/'.$jsFile))
            array_push($this->data['extraJsFiles'], $jsFile);
    }
}
