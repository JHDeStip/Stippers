/**
 * Created by Stan on 10/02/2015.
 */

function validateRenewUserSearchForm(form) {
    var element;
    var errorMessage;
    var inputElement;
    var valid = true;
    
    if (buttonClicked !== SUBMITBUTTON) {
        return true;
    }

    inputElement = form.first_name;
    if (inputElement.value.length > STRINGMAXLENGTH) {
        element = document.getElementById('form_label_error_first_name');
        if (!element) {
            errorMessage = document.createElement('label');
            errorMessage.setAttribute('class', 'form_label_error');
            errorMessage.setAttribute('for', 'first_name');
            errorMessage.setAttribute('id', 'form_label_error_first_name');
            errorMessage.appendChild(document.createTextNode('De voornaam mag maximaal uit '+STRINGMAXLENGTH+' karakters bestaan.'));
            inputElement.parentNode.insertBefore(errorMessage, inputElement.nextSibling);
        }
        else {
            element.innerHTML = 'De voornaam mag maximaal uit '+STRINGMAXLENGTH+' karakters bestaan.';
        }
        valid = false;
    }
    else {
        element =  document.getElementById('form_label_error_first_name');
        if (element) {
            element.parentNode.removeChild(element);
        }
    }

    inputElement = form.last_name;
    if (inputElement.value.length > STRINGMAXLENGTH) {
        element = document.getElementById('form_label_error_last_name');
        if (!element) {
            errorMessage = document.createElement('label');
            errorMessage.setAttribute('class', 'form_label_error');
            errorMessage.setAttribute('for', 'last_name');
            errorMessage.setAttribute('id', 'form_label_error_last_name');
            errorMessage.appendChild(document.createTextNode('De achternaam mag maximaal uit '+STRINGMAXLENGTH+' karakters bestaan.'));
            inputElement.parentNode.insertBefore(errorMessage, inputElement.nextSibling);
        }
        else {
            element.innerHTML = 'De achternaam mag maximaal uit '+STRINGMAXLENGTH+' karakters bestaan.';
        }
        valid = false;
    }
    else {
        element =  document.getElementById('form_label_error_last_name');
        if (element) {
            element.parentNode.removeChild(element);
        }
    }

    inputElement = form.email;
    if (inputElement.value.length > EMAILMAXLENGTH) {
        element = document.getElementById('form_label_error_email');
        if (!element) {
            errorMessage = document.createElement('label');
            errorMessage.setAttribute('class', 'form_label_error');
            errorMessage.setAttribute('for', 'email');
            errorMessage.setAttribute('id', 'form_label_error_email');
            errorMessage.appendChild(document.createTextNode('Het e-mailadres mag maximaal uit '+EMAILMAXLENGTH+' karakters bestaan.'));
            inputElement.parentNode.insertBefore(errorMessage, inputElement.nextSibling);
        }
        else {
            element.innerHTML = 'Het e-mailadres mag maximaal uit '+EMAILMAXLENGTH+' karakters bestaan.';
        }
        valid = false;
    }
    else {
        element =  document.getElementById('form_label_error_email');
        if (element) {
            element.parentNode.removeChild(element);
        }
    }

    return valid;
}