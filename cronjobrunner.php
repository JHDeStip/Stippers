<?php

/**
 * Location: /application/controllers
 * If the webhost allows only 1 cronjob this controller can be used to call all methods that should be run by a cronjob.
 */

if (!defined('BASEPATH'))
    echo('No direct script access allowed');

require_once 'import.php';

require_once __DIR__.'/../../members/app/config/GlobalConfig.php';
require_once __DIR__.'/../../members/app/runnables/balanceSummaryEmailSender/BalanceSummaryEmailSender.php';
require_once __DIR__.'/../../members/app/runnables/weeklyWinnerPicker/WeeklyWinnerPicker.php';
require_once __DIR__.'/../../members/app/runnables/monthlyWinnerEmailSender/MonthlyWinnerEmailSender.php';

//Job to refresh the calendar on the website with Facebook events, this can happen at every cron run
(new Import())->index();
        
//Get current time
$dateTime = new DateTime();
$dateTime->setTimeZone(new DateTimeZone(GlobalConfig::PHP_TIME_ZONE));

//Jobs to do weekly things every monday between 7 and 8 a.m.
if ($dateTime->format('w') == 1 && $dateTime->format('H') == 7) {
    //Send money transactions report email
    BalanceSummaryEmailSender::run();
    //Generate winner of the week
    WeeklyWinnerPicker::run();
}

//Jobs to do monthly things every first of the month between 7 and 8 a.m.
if ($dateTime->format('j') == 1 && $dateTime->format('H') == 7) {
    //Send monthly winner notification
    MonthlyWinnerEmailSender::run();
}