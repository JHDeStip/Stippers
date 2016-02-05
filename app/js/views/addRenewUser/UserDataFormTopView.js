/**
 * Created by Stan on 8/02/2015.
 */

function validateUserDataFormTop(form) {
    var element;
    var errorMessage;
    var inputElement;
    var valid = true;

    var element =  document.getElementById('user_data_form_error_message');
    if (element) {
        element.parentNode.removeChild(element);
    }

    if (buttonClicked !== SUBMITBUTTON) {
        return true;
    }

    inputElement = form.card_number;
    if (inputElement.value === '' || !(/^[0-9]{1,8}$/).exec(inputElement.value)) {
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

    inputElement = form.email;
    if (inputElement.value === '' || inputElement.value.length > EMAILMAXLENGTH) {
        element = document.getElementById('form_label_error_email');
        if (!element) {
            errorMessage = document.createElement('label');
            errorMessage.setAttribute('class', 'form_label_error');
            errorMessage.setAttribute('for', 'email');
            errorMessage.setAttribute('id', 'form_label_error_email');
            errorMessage.appendChild(document.createTextNode('Voer een geldig e-mailadres in.'));
            inputElement.parentNode.insertBefore(errorMessage, inputElement.nextSibling);
        }
        else {
            element.innerHTML = 'Voer een geldig e-mailadres in.';
        }
        valid = false;
    }
    else {
        element =  document.getElementById('form_label_error_email');
        if (element) {
            element.parentNode.removeChild(element);
        }
    }

    inputElement = form.repeat_email;
    if (inputElement.value !== form.email.value) {
        element = document.getElementById('form_label_error_repeat_email');
        if (!element) {
            errorMessage = document.createElement('label');
            errorMessage.setAttribute('class', 'form_label_error');
            errorMessage.setAttribute('for', 'repeat_email');
            errorMessage.setAttribute('id', 'form_label_error_repeat_email');
            errorMessage.appendChild(document.createTextNode('De twee e-mailadressen moeten gelijk zijn.'));
            inputElement.parentNode.insertBefore(errorMessage, inputElement.nextSibling);
        }
        else {
            element.innerHTML = 'De twee e-mailadressen moeten gelijk zijn.';
        }
        valid = false;
    }
    else {
        element =  document.getElementById('form_label_error_repeat_email');
        if (element) {
            element.parentNode.removeChild(element);
        }
    }
    
    return valid;
}