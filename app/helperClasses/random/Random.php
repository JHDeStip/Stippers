<?php
/**
 * Created by PhpStorm.
 * User: Stan
 * Date: 3/12/2014
 * Time: 12:36
 */

abstract class Random {
    const PASSWORDCHARACTERS='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789(){}[]-_/\\\'".,:;?!&$@';
    const DEFAULTPASSWORDLENGTH = 12;

    public static function getGuid()
    {
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

    public static function getPassword($length = Random::DEFAULTPASSWORDLENGTH, $characters = Random::PASSWORDCHARACTERS){
        $password = '';
        for ($i=0; $i<$length; $i++)
            $password .= substr($characters, rand(0, strlen($characters)-1), 1);
        return $password;
    }
}