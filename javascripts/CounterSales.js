function alTeclear(event) {
    var keyCode = event.keyCode || event.which; 
    if ( keyCode != 9 ) {
        return true;
    }
    
    event.preventDefault();
    var entry=$('.lastQkEntry'),inputQty=$('.lastQkEntry .entryQty'),date=$('.lastQkEntry .date').val()
    ,trigger=$('.lastQkEntry .entryCode');
    if(!trigger.val()){
        return;
    }
    inputQty.val(1);
    var parts=inputQty.attr("name").split("_");
    var i=Number(parts[parts.length-1])+1;
    /* OBTENER EL NUMERO DE ENTRADA QUE SE VA A GENERAR*/
    trigger.off("keydown");
    inputQty.off("keydown");

    entry.after(`<tr class="striped_row lastQkEntry">
                    <td><input type="text" name="part_${i}" data-type="no-illegal-chars"
                                 class="entryCode" size="21" maxlength="20" />
                    </td>               
                    <td><input type="text" class="number entryQty" name="qty_${i}" size="6"
                        maxlength="6" />
                        <input type="hidden" class="date" name="ItemDue_${i}" 
                        value="${date}" />
                        <div class="result"></div>
                    </td>
                </tr>`);
    entry.removeClass("lastQkEntry");
    var titulo=trigger.attr('title');
    var newone=$('.lastQkEntry .entryCode').on("keydown", alTeclear);
    $('.lastQkEntry .entryQty').on("keydown", alTeclear);
    newone.focus();
    newone.attr("title",titulo);
};
function setCambio(){
    var lastTotal=Number($('#LastTotalHdn').val()),
    given=Number($('#AmountGiven').val());
    $('#AmountPaid').val(given>lastTotal?lastTotal:given);
    $('#AmountPaid').trigger("change");
    $('#AmountReturn').val((given-lastTotal).toLocaleString('en'));
}

$(document).ready(function() {
    $('.lastQkEntry .entryCode').on("keydown", alTeclear);
    $('.lastQkEntry .entryQty').on("keydown", alTeclear);
    $('#AmountGiven').on("keyup", setCambio);
});