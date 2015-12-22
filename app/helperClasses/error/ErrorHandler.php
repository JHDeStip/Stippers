<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * This class handles php errors.
 */

abstract class ErrorHandler {
    
    public static function exceptionErrorHandler($errno, $errstr, $errfile, $errline) {
        throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
    }
    
    /**
     * Executes when php encounters a fatal error. This is used to show a 'nice' error page.
     */
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