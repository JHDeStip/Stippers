/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * This file contains code to validate a form.
 */

function validateNewMessageForm(form) {
    var element;
    var errorMessage;
    var inputElement;
    var valid = true;

    if (buttonClicked !== SUBMIT_BUTTON) {
        return true;
    }

    inputElement = form.new_message;
    if (inputElement.value == '' || inputElement.value.length > CHAT_MESSAGE_MAX_LENGTH) {
        element = document.getElementById('new_message_form_error_message');
        if (!element) {
            errorMessage = document.createElement('h2');
            errorMessage.setAttribute('class', 'error_message');
            errorMessage.setAttribute('id', 'new_message_form_error_message');
            errorMessage.appendChild(document.createTextNode('Geef een geldig bericht in.'));
            formElement = document.getElementById('new_message_form');
            formElement.parentNode.insertBefore(errorMessage, formElement);
        }
        else {
            element.innerHTML = 'Geef een geldig bericht in.';
        }
        valid = false;
    }
    else {
        element =  document.getElementById('new_message_form_error_message');
        
        if (element) {
            element.parentNode.removeChild(element);
        }
    }

    return valid;
}