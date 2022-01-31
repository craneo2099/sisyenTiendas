function setPriceNoTax(){
    var inPrice=Number($('#PriceTx').val());
    $('#PriceNoTax').val((inPrice/1.16).toLocaleString('en'));
}

$(document).ready(function() {
    $('#PriceTx').on("keyup", setPriceNoTax);
});