/**
 * Created by Stan on 11/02/2015.
 */

function validateLoginForm(form) {
    var element;
    var errorMessage;
    var valid = true;

    element =  document.getElementById('login_form_error_message');
    if (element) {
        element.parentNode.removeChild(element);
    }

    if (buttonClicked !== SUBMITBUTTON) {
        return true;
    }

    if (form.email.value.length > EMAILMAXLENGTH || form.password.value.length < PASSWORDMINLENGTH || form.password.value.length > PASSWORDMAXLENGTH) {
        element = null;
        element = document.getElementById('login_form_error_message');
        if (!element) {
            errorMessage = document.createElement('h2');
            errorMessage.setAttribute('class', 'error_message');
            errorMessage.setAttribute('id', 'login_form_error_message');
            errorMessage.appendChild(document.createTextNode('E-mailadres en/of wachtwoord onjuist.'));
            form = document.getElementById('login_form');
            form.parentNode.insertBefore(errorMessage, form);
        }
        valid = false;
    }

    return valid;
}