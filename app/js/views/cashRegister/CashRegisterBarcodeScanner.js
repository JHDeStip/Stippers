/**
 * This file is part of the Stippers project (available here: https://github.com/Stannieman/stippers/).
 * The license and all terms en conditions that apply to Stippers also apply to this file.
 * 
 * @author Stan Wijckmans
 * 
 * This file contains code to attach the barcode detection code to the card_number input element.
 */

function cashRegisterEnterCardFormLoadBarcodeScanner() {
    var cardNumberInput = document.getElementById('card_number');
    if (cardNumberInput) {
        submitButton = document.getElementById('enter_card_form_submit');
        textBox = cardNumberInput;
        cardNumberInput.addEventListener('input', barcodeScannerInputChanged);
        cardNumberInput.focus();
    }
};