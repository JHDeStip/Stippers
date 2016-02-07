/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * This file contains code to validate a form.
 */

function validateAddEditBrowserForm(form) {
    var element;
    var errorMessage;
    var inputElement;
    var valid = true;
    
    element =  document.getElementById('add_edit_browser_form_error_message');
    if (element) {
        element.parentNode.removeChild(element);
    }

    if (buttonClicked !== SUBMIT_BUTTON) {
        return true;
    }
    
    inputElement = form.browser_name;
    if (inputElement.value === '' || inputElement.value.length > STRINGLENGTH) {
        element = document.getElementById('form_label_error_browser_name');
        if (!element) {
            errorMessage = document.createElement('label');
            errorMessage.setAttribute('class', 'form_label_error');
            errorMessage.setAttribute('for', 'browser_name');
            errorMessage.setAttribute('id', 'form_label_error_browser_name');
            errorMessage.appendChild(document.createTextNode('Voer een geldige browsernaam in.'));
            inputElement.parentNode.insertBefore(errorMessage, inputElement.nextSibling);
        }
        else {
            element.innerHTML = 'Voer een geldige browsernaam in.';
        }
        valid = false;
    }
    else {
        element =  document.getElementById('form_label_error_browser_name');
        if (element) {
            element.parentNode.removeChild(element);
        }
    }

    return valid;
}