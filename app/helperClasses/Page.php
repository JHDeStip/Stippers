<?php

class Page {
    public $views = array();
    public $data = null;
        
    public function showBasic() {
        require_once __DIR__.'/../views/common/Header.html' ;
        foreach ($this->views as $view)
            require_once __DIR__.'/../views/'.$view.'.html';
        require_once __DIR__.'/../views/common/Footer.html';
    }
    
    public function addView($viewName) {
        array_push($this->views, $viewName);
    }
}
