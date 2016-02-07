/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * This file contains code to validate a form.
 */

function validateEmailUploadForm(form) {
    var element;
    var errorMessage;
    var inputElement;
    var valid = true;
    
    element = document.getElementById('email_upload_form_error_message');
    
    inputElement = form.email_file;
    if (inputElement.files.length === 0) {
        if (!element) {
            errorMessage = document.createElement('h2');
            errorMessage.setAttribute('class', 'error_message');
            errorMessage.setAttribute('id', 'email_upload_form_error_message');
            errorMessage.appendChild(document.createTextNode('Je hebt geen bestand geselecteerd.'));
            form.parentNode.insertBefore(errorMessage, form);
        }
        else {
             element.innerHTML = 'Je hebt geen bestand geselecteerd.';
        }
        valid = false;
    }
    else if (inputElement.files[0].size > EMAIL_FILE_MAX_SIZE) {
        if (!element) {
            errorMessage = document.createElement('h2');
            errorMessage.setAttribute('class', 'error_message');
            errorMessage.setAttribute('id', 'email_upload_form_error_message');
            errorMessage.appendChild(document.createTextNode('Het bestand moet kleiner zijn dan 1MB.'));
            form.parentNode.insertBefore(errorMessage, form);
        }
        else {
             element.innerHTML = 'Het bestand moet kleiner zijn dan 1MB.';
        }
        valid = false;
    }
    
    if (valid && element) {
        element.parentNode.removeChild(element);
    }
    
    return valid;
}