<?php

interface IValidator
{
    public static function validate(array $data);
    
    public static function initErrMsgs();
}
