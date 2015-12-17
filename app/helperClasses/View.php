<?php

abstract class View {

    public static function showBasicView(array $viewNames, array $data) {
        require_once __DIR__."/../views/common/Header.html";
        foreach ($viewNames as $viewName)
            require_once __DIR__."/../views/".$viewName.".html";
        require_once __DIR__."/../views/common/Footer.html";
    }
}
