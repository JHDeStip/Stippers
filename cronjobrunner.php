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

class CronJobRunner extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
	}
    
    public function index()
	{
        //Job to refresh the calendar on the website with Facebook events
        //(new Import())->index();
        
        //Job to send the weekly money overview email every monday at between 7a.m.
        //Get current time
        $dateTime = new DateTime();
        $dateTime->setTimeZone(new DateTimeZone(GlobalConfig::PHP_TIME_ZONE));
        //Check if monday and the hour is 7
        if ($dateTime->format('w') == 3 && $dateTime->format('H') == 20)
            echo "ha";
            BalanceSummaryEmailSender::run();
    }
}