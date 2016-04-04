<?php

/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * This file contains settings for the weekly winner feature.
 */

abstract class WeeklyWinnerConfig {
    // Users can only be the weekly winner once every MIN_WINNING_INTERVAL times
    const MIN_WINNING_INTERVAL = 10;
}