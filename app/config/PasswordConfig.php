<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * This file contains settings for creating new passwords.
 */

abstract class PasswordConfig {
    // The characters that may appear in passwords
    const PASSWORD_CHARACTERS = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789(){}[]-_/\\"\'.,:;?!&$@';
}