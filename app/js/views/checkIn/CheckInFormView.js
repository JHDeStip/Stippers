/**
 * Created by Stan on 10/02/2015.
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

    if (buttonClicked !== SUBMITBUTTON) {
        return true;
    }

    inputElement = form.card_number;
    if (inputElement.value === '' || !(/^[0-9]{1,8}$/).exec(inputElement.value)) {
        inputElement.value = '';
        element = document.getElementById('form_label_error_card_number');
        if (!element) {
            errorMessage = document.createElement('label');
            errorMessage.setAttribute('class', 'form_label_error');
            errorMessage.setAttribute('for', 'first_name');
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