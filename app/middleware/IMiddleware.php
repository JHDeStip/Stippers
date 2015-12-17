<?php

interface IMiddleware {
    public static function run(array $requestData);
}
