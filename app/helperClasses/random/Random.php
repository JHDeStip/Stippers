<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Class to generate random numbers, GUIDs, passwords etc.
 */

require_once __DIR__.'/../../config/SecurityConfig.php';
require_once __DIR__.'/../../config/PasswordConfig.php';

abstract class Random {

    /**
     * Returns a GUID
     * 
     * @return string GUID
     */
    public static function getGuid() {
        $randomString = openssl_random_pseudo_bytes(16);
        $time_low = bin2hex(substr($randomString, 0, 4));
        $time_mid = bin2hex(substr($randomString, 4, 2));
        $time_hi_and_version = bin2hex(substr($randomString, 6, 2));
        $clock_seq_hi_and_reserved = bin2hex(substr($randomString, 8, 2));
        $node = bin2hex(substr($randomString, 10, 6));

        $time_hi_and_version = hexdec($time_hi_and_version);
        $time_hi_and_version = $time_hi_and_version >> 4;
        $time_hi_and_version = $time_hi_and_version | 0x4000;

        $clock_seq_hi_and_reserved = hexdec($clock_seq_hi_and_reserved);
        $clock_seq_hi_and_reserved = $clock_seq_hi_and_reserved >> 2;
        $clock_seq_hi_and_reserved = $clock_seq_hi_and_reserved | 0x8000;

        return sprintf('%08s-%04s-%04x-%04x-%012s', $time_low, $time_mid, $time_hi_and_version, $clock_seq_hi_and_reserved, $node);
    }

    /**
     * Generates a new random password.
     * 
     * @param int $length length of the new password
     * @param string $characters characters that can appear in the generated password
     * @return string generated password
     */
    public static function getPassword($length = DataValidationConfig::PASSWORD_MIN_LENGTH, $characters = PasswordConfig::PASSWORD_CHARACTERS){
        $password = '';
        for ($i=0; $i<$length; $i++)
            $password .= substr($characters, rand(0, strlen($characters)-1), 1);
        return $password;
    }
}