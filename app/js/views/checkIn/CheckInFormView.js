/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * This file contains code to validate a form.
 */

function validateCheckInForm(form) {
    var element;
    var errorMessage;
    var inputElement;
    var valid = true;
    
    element =  document.getElementById('check_in_form_error_message');
    if (element) {
        element.parentNode.removeChild(element);
    }

    if (buttonClicked !== SUBMIT_BUTTON) {
        return true;
    }

    inputElement = form.card_number;
    if (!(/^[0-9]{1,8}$/).exec(inputElement.value)) {
        inputElement.value = '';
        element = document.getElementById('form_label_error_card_number');
        if (!element) {
            errorMessage = document.createElement('label');
            errorMessage.setAttribute('class', 'form_label_error');
            errorMessage.setAttribute('for', 'card_number');
            errorMessage.setAttribute('id', 'form_label_error_card_number');
            errorMessage.appendChild(document.createTextNode('Voer een geldig kaartnummer in.'));
            inputElement.parentNode.insertBefore(errorMessage, inputElement.nextSibling);
        }
        else {
            element.innerHTML = 'Voer een geldig kaartnummer in.';
        }
        valid = false;
    }
    else {
        element =  document.getElementById('form_label_error_card_number');
        if (element) {
            element.parentNode.removeChild(element);
        }
    }

    return valid;
}