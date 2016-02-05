/**
 * Created by Stan on 11/02/2015.
 */

function userDataFormLoadBarcodeScanner() {
    var cardNumberInput = document.getElementById("card_number");
    if (cardNumberInput) {
        textBox = cardNumberInput;
        cardNumberInput.addEventListener("input", barcodeScannerInputChanged);
        cardNumberInput.focus();
    }
};