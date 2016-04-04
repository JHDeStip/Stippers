/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * This file contains code to validate a form.
 */

function formSubmit(form){
    if (buttonClicked == SUBMIT_BUTTON) {
        return validateProfileForm(form);
    }
    else if (buttonClicked == BACK_BUTTON) {
        window.location.href='manageuser';
        return false;
    }
    else if (buttonClicked == EDIT_BUTTON) {
        enableControls(form);
        return false;
    }
    else if (buttonClicked == CANCEL_BUTTON) {
        disableControls(form);
        return false;
    }
    else {
        return true;
    }
}

function validateProfileForm(form) {
    var element;

    element =  document.getElementById('profile_form_error_message');
    if (element) {
        element.parentNode.removeChild(element);
    }

    var errorMessage;
    var inputElement;

    var valid = true;
    inputElement = form.email;
    if (inputElement.value === '') {
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
            errorMessage.appendChild(document.createTextNode('Voer een geldige straartnaam in.'));
            inputElement.parentNode.insertBefore(errorMessage, inputElement.nextSibling);
        }
        else {
            element.innerHTML = 'Voer een geldige straartnaam in.';
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
            inputElement = document.getElementById('postal_code');
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

    inputElement = form.country;
    if (inputElement.value === '') {
        element = document.getElementById('form_label_error_country');
        if (!element) {
            errorMessage = document.createElement('label');
            errorMessage.setAttribute('class', 'form_label_error');
            errorMessage.setAttribute('for', 'country');
            errorMessage.setAttribute('id', 'form_label_error_country');
            errorMessage.appendChild(document.createTextNode('Voer een geldig land in.'));
            inputElement.parentNode.insertBefore(errorMessage, inputElement.nextSibling);
        }
        else {
            element.innerHTML = 'Voer een geldig land in.';
        }
        valid = false;
    }
    else {
        element =  document.getElementById('form_label_error_country');
        if (element) {
            element.parentNode.removeChild(element);
        }
    }

    inputElement = form.phone;
    if (inputElement.value !== '' && !(/^[0-9]{9,16}$/).exec(inputElement.value)) {
        element = document.getElementById('form_label_error_phone');
        if (!element) {
            errorMessage = document.createElement('label');
            errorMessage.setAttribute('class', 'form_label_error');
            errorMessage.setAttribute('for', 'phone');
            errorMessage.setAttribute('id', 'form_label_error_phone');
            errorMessage.appendChild(document.createTextNode('Voer een geldig telefoonnummer in.'));
            inputElement = document.getElementById('phone');
            inputElement.parentNode.insertBefore(errorMessage, inputElement.nextSibling);
        }
        else {
            element.innerHTML = 'Voer een geldig telefoonnummer in.';
        }
        valid = false;
    }
    else {
        element =  document.getElementById('form_label_error_phone');
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

function enableControls(form) {
    form.email.disabled = false;
    form.repeat_email.disabled = false;
    form.first_name.disabled = false;
    form.last_name.disabled = false;
    form.street.disabled = false;
    form.house_number.disabled = false;
    form.city.disabled = false;
    form.postal_code.disabled = false;
    form.country.disabled = false;
    form.phone.disabled = false;
    form.date_of_birth.disabled = false;

    if (form.is_admin) {
        form.is_admin.disabled = false;
    }
/*
    if (form.is_hint_manager) {
        form.is_hint_manager.disabled = false;
    }
*/
    if (form.is_user_manager) {
        form.is_user_manager.disabled = false;
    }

    if (form.is_browser_manager) {
        form.is_browser_manager.disabled = false;
    }
    
    if (form.is_money_manager) {
        form.is_money_manager.disabled = false;
    }

    var element;
    element = form.edit_user_form_edit;
    if (element) {
        element.parentNode.removeChild(element);
    }

    element = form.edit_user_form_back_to_search_results;
    if (element) {
        element.parentNode.removeChild(element);
    }


    var saveButton = document.createElement('input');
    saveButton.setAttribute('class', 'submit_button');
    saveButton.setAttribute('type', 'submit');
    saveButton.setAttribute('name', 'save');
    saveButton.setAttribute('id', 'edit_user_form_save');
    saveButton.setAttribute('value', 'Opslaan');
    saveButton.onclick = function(){buttonClicked = SUBMIT_BUTTON;}
    form.appendChild(saveButton);

    var cancelButton = document.createElement('input');
    cancelButton.setAttribute('class', 'submit_button');
    cancelButton.setAttribute('type', 'submit');
    cancelButton.setAttribute('name', 'cancel');
    cancelButton.setAttribute('id', 'edit_user_form_cancel');
    cancelButton.setAttribute('value', 'Annuleren');
    cancelButton.onclick = function(){buttonClicked = CANCEL_BUTTON;}
    form.appendChild(cancelButton);
}

function disableControls(form) {
    form.email.disabled = true;
    form.repeat_email.disabled = true;
    form.first_name.disabled = true;
    form.last_name.disabled = true;
    form.street.disabled = true;
    form.house_number.disabled = true;
    form.city.disabled = true;
    form.postal_code.disabled = true;
    form.country.disabled = true;
    form.phone.disabled = true;
    form.date_of_birth.disabled = true;

    if (form.is_admin) {
        form.is_admin.disabled = true;
    }
/*
    if (form.is_hint_manager) {
        form.is_hint_manager.disabled = true;
    }
*/
    if (form.is_user_manager) {
        form.is_user_manager.disabled = true;
    }

    if (form.is_browser_manager) {
        form.is_browser_manager.disabled = true;
    }
    
    if (form.is_money_manager) {
        form.is_money_manager.disabled = true;
    }
    
    var element;
    element = form.edit_user_form_save;
    if (element) {
        element.parentNode.removeChild(element);
    }

    element = form.edit_user_form_cancel;
    if (element) {
        element.parentNode.removeChild(element);
    }
    
    var editButton = document.createElement('input');
    editButton.setAttribute('class', 'submit_button');
    editButton.setAttribute('type', 'submit');
    editButton.setAttribute('name', 'edit');
    editButton.setAttribute('id', 'edit_user_form_edit');
    editButton.setAttribute('value', 'Bewerken');
    editButton.onclick = function(){buttonClicked = EDIT_BUTTON;}
    form.appendChild(editButton);
    
    var backToSearchResultsButton = document.createElement('input');
    backToSearchResultsButton.setAttribute('class', 'submit_button');
    backToSearchResultsButton.setAttribute('type', 'submit');
    backToSearchResultsButton.setAttribute('name', 'back_to_search_results');
    backToSearchResultsButton.setAttribute('id', 'edit_user_form_back_to_search_results');
    backToSearchResultsButton.setAttribute('value', 'Terug naar zoekresultaten');
    backToSearchResultsButton.onclick = function(){buttonClicked = BACK_BUTTON;}
    form.appendChild(backToSearchResultsButton);
    
}