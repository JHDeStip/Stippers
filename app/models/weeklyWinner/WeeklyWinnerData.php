<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * Class containing data about the current winner of the week.
 */

class WeeklyWinnerData {
    public $startOfWeek;
    public $userId;
    public $hasCollectedPrize;
    
    public function __construct($startOfWeek, $userId, $hasCollectedPrize) {
        $this->startOfWeek = $startOfWeek;
        $this->userId = $userId;
        $this->hasCollectedPrize = $hasCollectedPrize;
    }
}