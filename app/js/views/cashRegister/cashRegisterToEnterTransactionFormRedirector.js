/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * This file contains the code to automatically redirect to the enter transaction form after a card is scanned.
 */

var REDIRECTION_DELAY = 1500;

function checkInFormLoadRedirector() {
    var successfulCheckInElement = document.getElementById('successfulCheckIn');
    if (successfulCheckInElement) {
        setTimeout(function(){window.location.href='checkin';}, REDIRECTION_DELAY);
    }
};