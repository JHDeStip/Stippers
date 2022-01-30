<?php

require_once __DIR__.'/../members/app/config/GlobalConfig.php';
require_once __DIR__.'/../members/app/runnables/balanceSummaryEmailSender/BalanceSummaryEmailSender.php';
require_once __DIR__.'/../members/app/runnables/weeklyWinnerPicker/WeeklyWinnerPicker.php';
require_once __DIR__.'/../members/app/runnables/monthlyWinnerEmailSender/MonthlyWinnerEmailSender.php';

//Get current time
$dateTime = new DateTime();
$dateTime->setTimeZone(new DateTimeZone(GlobalConfig::PHP_TIME_ZONE));

//Jobs to do weekly things every monday between 7 and 8 a.m.
if ($dateTime->format('w') == 1 && $dateTime->format('H') == 7) {
    //Send money transactions report email
    //BalanceSummaryEmailSender::run();
    //Generate winner of the week
    WeeklyWinnerPicker::run();
}

//Jobs to do monthly things every first of the month between 7 and 8 a.m.
if ($dateTime->format('j') == 1 && $dateTime->format('H') == 7) {
    //Send monthly winner notification
    MonthlyWinnerEmailSender::run();
}

?>