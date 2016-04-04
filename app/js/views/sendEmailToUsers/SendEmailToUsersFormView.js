/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * This file contains code to validate a form.
 */

function validateSendEmailToUsersForm(form) {
    var element;
    var errorMessage;
    var inputElement;
    var valid = true;

    inputElement = form.subject;
    if (inputElement.value === '') {
        element = document.getElementById('form_label_error_subject');
        if (!element) {
            errorMessage = document.createElement('label');
            errorMessage.setAttribute('class', 'form_label_error');
            errorMessage.setAttribute('for', 'subject');
            errorMessage.setAttribute('id', 'form_label_error_subject');
            errorMessage.appendChild(document.createTextNode('Voer een geldig onderwerp in.'));
            inputElement.parentNode.insertBefore(errorMessage, inputElement.nextSibling);
        }
        else {
            element.innerHTML = 'Voer een geldig onderwerp in.';
        }
        valid = false;
    }
    else {
        element =  document.getElementById('form_label_error_subject');
        if (element) {
            element.parentNode.removeChild(element);
        }
    }
    
    return valid;
}