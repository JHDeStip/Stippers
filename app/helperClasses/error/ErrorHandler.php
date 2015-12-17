<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

abstract class ErrorHandler {
    public static function exceptionErrorHandler($errno, $errstr, $errfile, $errline) {
        throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
    }
    
    public static function fatalErrorHandler() {
        if (error_get_last()) {
            ?>
        <h2 class='error_message' id='fatal_error'>Er is iets misgegaan :(</h2>
        </div>
        </body>
        </html>
        <?php
        }
    }
}