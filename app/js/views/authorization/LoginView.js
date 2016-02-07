/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * This file contains code to validate a form.
 */

function validateLoginForm(form) {
    var element;
    var errorMessage;
    var valid = true;

    if (buttonClicked !== SUBMIT_BUTTON) {
        return true;
    }
    
    element = document.getElementById('login_form_error_message');

    if (form.password.value.length < PASSWORD_MIN_LENGTH) {
        if (!element) {
            errorMessage = document.createElement('h2');
            errorMessage.setAttribute('class', 'error_message');
            errorMessage.setAttribute('id', 'login_form_error_message');
            errorMessage.appendChild(document.createTextNode('E-mailadres en/of wachtwoord onjuist.'));
            form.parentNode.insertBefore(errorMessage, form);
        }
        else
            element.innerHTML = 'E-mailadres en/of wachtwoord onjuist';
        valid = false;
    }
    
    if (valid && element)
        element.parentNode.removeChild(element);

    return valid;
}