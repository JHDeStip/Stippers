/**
 * Created by Stan on 11/02/2015.
 */

var BARCODE_LENGTH = 8;
var INPUT_DELAY = 50;

var previousLength = 0;
var referenceTime;
var stillValid = true;
var textBox;
var submitButton;

function barcodeScannerListener(){
    if (stillValid){
        if (textBox.value.length === 1){
            referenceTime = (new Date()).getTime();
            previousLength = 1;
        }
        else if (textBox.value.length !== ++previousLength){
            stillValid = false;
        }
        else {
            if (textBox.value.length == barcodeLength){
                if ((new Date()).getTime() - referenceTime <= inputDelay) {
                    textBox.removeEventListener('input', barcodeScannerListener);
                    textBox.value = convertInput(textBox.value);
                    textBox.addEventListener('input', barcodeScannerListener);
                    if (submitButton) {
                        submitButton.click();
                    }
                }
            }
        }
    }
}

function convertInput(input) {
    var convertedText = "";

    for (var i=0; i<input.length; i++) {
        switch (input.substr(i, 1)) {
            case '&':
                convertedText += '1';
                break;
            case 'é':
                convertedText += '2';
                break;
            case '"':
                convertedText += '3';
                break;
            case '\'':
                convertedText += '4';
                break;
            case '(':
                convertedText += '5';
                break;
            case '§':
                convertedText += '6';
                break;
            case 'è':
                convertedText += '7';
                break;
            case '!':
                convertedText += '8';
                break;
            case 'ç':
                convertedText += '9';
                break;
            case 'à':
                convertedText += '0';
                break;
            default:
                convertedText += input.substr(i, 1);
                break;
        }
    }
    return convertedText;
}