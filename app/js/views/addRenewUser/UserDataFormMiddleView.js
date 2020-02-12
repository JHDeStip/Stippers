/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * This file contains code to validate a form.
 */

function validateUserDataFormMiddle(form) {
    var element;
    var errorMessage;
    var inputElement;
    var valid = true;

    inputElement = form.first_name;
    if (inputElement.value === '') {
        element = document.getElementById('form_label_error_first_name');
        if (!element) {
            errorMessage = document.createElement('label');
            errorMessage.setAttribute('class', 'form_label_error');
            errorMessage.setAttribute('for', 'first_name');
            errorMessage.setAttribute('id', 'form_label_error_first_name');
            errorMessage.appendChild(document.createTextNode('Voer een geldige voornaam in.'));
            inputElement.parentNode.insertBefore(errorMessage, inputElement.nextSibling);
        }
        else {
            element.innerHTML = 'Voer een geldige voornaam in.';
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
    if (inputElement.value === '') {
        element = document.getElementById('form_label_error_last_name');
        if (!element) {
            errorMessage = document.createElement('label');
            errorMessage.setAttribute('class', 'form_label_error');
            errorMessage.setAttribute('for', 'last_name');
            errorMessage.setAttribute('id', 'form_label_error_last_name');
            errorMessage.appendChild(document.createTextNode('Voer een geldige achternaam in.'));
            inputElement.parentNode.insertBefore(errorMessage, inputElement.nextSibling);
        }
        else {
            element.innerHTML = 'Voer een geldige achternaam in.';
        }
        valid = false;
    }
    else {
        element =  document.getElementById('form_label_error_last_name');
        if (element) {
            element.parentNode.removeChild(element);
        }
    }

    inputElement = form.street;
    if (inputElement.value === '') {
        element = document.getElementById('form_label_error_street');
        if (!element) {
            errorMessage = document.createElement('label');
            errorMessage.setAttribute('class', 'form_label_error');
            errorMessage.setAttribute('for', 'street');
            errorMessage.setAttribute('id', 'form_label_error_street');
            errorMessage.appendChild(document.createTextNode('Voer een geldige straatnaam in.'));
            inputElement.parentNode.insertBefore(errorMessage, inputElement.nextSibling);
        }
        else {
            element.innerHTML = 'Voer een geldige straatnaam in.';
        }
        valid = false;
    }
    else {
        element =  document.getElementById('form_label_error_street');
        if (element) {
            element.parentNode.removeChild(element);
        }
    }

    inputElement = form.house_number;
    if (inputElement.value === '') {
        element = null;
        element = document.getElementById('form_label_error_house_number');
        if (!element) {
            errorMessage = document.createElement('label');
            errorMessage.setAttribute('class', 'form_label_error');
            errorMessage.setAttribute('for', 'house_number');
            errorMessage.setAttribute('id', 'form_label_error_house_number');
            errorMessage.appendChild(document.createTextNode('Voer een geldig huisnummer in.'));
            inputElement.parentNode.insertBefore(errorMessage, inputElement.nextSibling);
        }
        else {
            element.innerHTML = 'Voer een geldig huisnummer in.';
        }
        valid = false;
    }
    else {
        element =  document.getElementById('form_label_error_house_number');
        if (element) {
            element.parentNode.removeChild(element);
        }
    }

    inputElement = form.city;
    if (inputElement.value === '') {
        element = document.getElementById('form_label_error_city');
        if (!element) {
            errorMessage = document.createElement('label');
            errorMessage.setAttribute('class', 'form_label_error');
            errorMessage.setAttribute('for', 'city');
            errorMessage.setAttribute('id', 'form_label_error_city');
            errorMessage.appendChild(document.createTextNode('Voer een geldige gemeente in.'));
            inputElement.parentNode.insertBefore(errorMessage, inputElement.nextSibling);
        }
        else {
            element.innerHTML = 'Voer een geldige gemeente in.';
        }
        valid = false;
    }
    else {
        element =  document.getElementById('form_label_error_city');
        if (element) {
            element.parentNode.removeChild(element);
        }
    }

    inputElement = form.postal_code;
    if (inputElement.value.length < POSTAL_CODE_MIN_LENGTH) {
        element = document.getElementById('form_label_error_postal_code');
        if (!element) {
            errorMessage = document.createElement('label');
            errorMessage.setAttribute('class', 'form_label_error');
            errorMessage.setAttribute('for', 'postal_code');
            errorMessage.setAttribute('id', 'form_label_error_postal_code');
            errorMessage.appendChild(document.createTextNode('Voer een geldige postcode in.'));
            inputElement.parentNode.insertBefore(errorMessage, inputElement.nextSibling);
        }
        else {
            element.innerHTML = 'Voer een geldige postcode in.';
        }
        valid = false;
    }
    else {
        element =  document.getElementById('form_label_error_postal_code');
        if (element) {
            element.parentNode.removeChild(element);
        }
    }

    inputElement = form.date_of_birth;
    if (!(/^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/).exec(inputElement.value)) {
        createDateOfBirthErrorMessage();
        valid = false;
    }
    else {
        var day = inputElement.value.substring(0, 2);
        var month = inputElement.value.substring(3, 5);
        var year = inputElement.value.substring(6, 10);
        if (!checkDate(day, month, year) || new Date(year, month, day) > new Date()) {
            createDateOfBirthErrorMessage();
            valid = false;
        }
        else {
            element =  document.getElementById('form_label_error_date_of_birth');
            if (element) {
                element.parentNode.removeChild(element);
            }
        }
    }

    return valid;
}

function createDateOfBirthErrorMessage(){
    var element = document.getElementById('form_label_error_date_of_birth');
    if (!element) {
        var errorMessage = document.createElement('label');
        errorMessage.setAttribute('class', 'form_label_error');
        errorMessage.setAttribute('for', 'date_of_birth');
        errorMessage.setAttribute('id', 'form_label_error_date_of_birth');
        errorMessage.appendChild(document.createTextNode('Voer een geldige geboortedatum in.'));
        var inputElement = document.getElementById('date_of_birth').nextSibling;
        inputElement.parentNode.insertBefore(errorMessage, inputElement.nextSibling);
    }
    else {
        element.innerHTML = 'Voer een geldige geboortedatum in.';
    }
}