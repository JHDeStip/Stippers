/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * This file contains code to periodically refresh the messages list in the chat view..
 */

var REFRESH_INTERVAL = 5000;
var REQUEST_READY_STATE_DONE = 4;
var HTTP_OK_CODE = 200;

var chatMessageListTable;
var chatMessageListContainer;
var refreshInterval;

/**
 * Method to load all eventhandlers and elements for the chat window to function correctly.
 */
function chatViewLoadMessageListRefresher() {    
    //Add eventandlers to stop/start refreshing on window blur/focus
    window.addEventListener('blur', onWindowBlur);
    window.addEventListener('focus', onWindowFocus);
    
    //Get messages table and container elements
    chatMessageListTable = document.getElementById('chat_message_list');
    chatMessageListContainer = document.getElementById('chat_message_list_container');
    
    //Add scroll handler to table container
    chatMessageListContainer.addEventListener('scroll', onMessageListScrollChanged);
    
    //Start updating message list
    startRefreshing();
};

/**
 * Method to refresh the message list
 */
function refresh() {
    //Create request
    var request =  new XMLHttpRequest();
    request.open('GET', 'chatmessages', true);
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

    //Add handler for if request received
    request.onreadystatechange = function() {
        //If request is ok set table content to response
        if (request.readyState == REQUEST_READY_STATE_DONE && request.status == HTTP_OK_CODE)
            chatMessageListTable.innerHTML = request.responseText;
    };
    
    //Send request
    request.send();
};

/**
 * Method for if the window loses focus.
 */
function onWindowBlur() {
    //Stop refreshing
    stopRefreshing();
};

/**
 * Method for if the window gets focus.
 */
function onWindowFocus() {
    //Start refreshing if we're at the top of the scroll container
    if (chatMessageListContainer.scrollTop == 0)
        startRefreshing();
}

/**
 * Method to handle scroll changed events for the scroll container.
 */
function onMessageListScrollChanged() {
    //Start refreshing of we scroll all the way to the top, otherwise stop refreshing.
    if (chatMessageListContainer.scrollTop == 0)
        startRefreshing();
    else
        stopRefreshing();
};

/**
 * Method to stop refreshing the message list.
 */
function stopRefreshing() {
    clearInterval(refreshInterval);
};

/**
 * Method to start refreshing the message list.
 */
function startRefreshing() {
    refresh();
    refreshInterval = setInterval(refresh, REFRESH_INTERVAL);
};