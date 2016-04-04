/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * This file contains code to validate a form.
 */

function validateEnterTransactionForm(form) {
    var element;
    var errorMessage;
    var inputElement;
    var valid = true;
    
    element = document.getElementById('enter_transaction_form_error_message');
    
    inputElement = form.decrease_money;
    if (inputElement.value === '') {
        if (!element) {
            errorMessage = document.createElement('h2');
            errorMessage.setAttribute('class', 'error_message');
            errorMessage.setAttribute('id', 'enter_transaction_form_error_message');
            errorMessage.appendChild(document.createTextNode('Je hebt geen transactie ingegeven.'));
            form.parentNode.insertBefore(errorMessage, form);
        }
        else {
             element.innerHTML = 'Je hebt geen transactie ingegeven.';
        }
        
        valid = false;
    }
    else if (isNaN(inputElement.value)) {
        if (!element) {
            errorMessage = document.createElement('h2');
            errorMessage.setAttribute('class', 'error_message');
            errorMessage.setAttribute('id', 'enter_transaction_form_error_message');
            errorMessage.appendChild(document.createTextNode('Voer een geldig bedrag in.'));
            form.parentNode.insertBefore(errorMessage, form);
        }
        else {
             element.innerHTML = 'Voer een geldig bedrag in.';
        }
        
        valid = false;
    }
    else if (inputElement.value < 0) {
        if (!element) {
            errorMessage = document.createElement('h2');
            errorMessage.setAttribute('class', 'error_message');
            errorMessage.setAttribute('id', 'enter_transaction_form_error_message');
            errorMessage.appendChild(document.createTextNode('Je kan enkel een positief bedrag ingeven.'));
            form.parentNode.insertBefore(errorMessage, form);
        }
        else {
             element.innerHTML = 'Je kan enkel een positief bedrag ingeven.';
        }
        
        valid = false;
    }
    
    return valid;
}