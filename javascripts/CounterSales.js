let puedeBuscarPiezas=true;
function triggerEntry() {
    var e = $.Event('keydown');
    e.which = 9; // Character 'A'
    $('.lastQkEntry .entryCode').trigger(e);
}
function alTeclear(event) {
    var keyCode = event.keyCode || event.which; 
    if ( keyCode != 9 ) {
        return true;
    }
    
    event.preventDefault();
    var entry=$('.lastQkEntry'),inputQty=$('.lastQkEntry .entryQty'),date=$('.lastQkEntry .date').val()
    ,trigger=$('.lastQkEntry .entryCode'),sbmtBtn=$('#QuickEntry');
    if(!trigger.val()){
        return;
    }
    if(!inputQty.val())
        inputQty.val(1);
    var parts=inputQty.attr("name").split("_");
    var i=Number(parts[parts.length-1])+1;
    /* OBTENER EL NUMERO DE ENTRADA QUE SE VA A GENERAR*/
    trigger.off("keydown");
    inputQty.off("keydown");
    $('#adderLink').remove();

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
                    <td><a id="adderLink" href="#" class="icon-inline" onclick="triggerEntry()">
                        <i class="fas fa-plus-circle"></i></a>
                    </td>
                </tr>`);
    entry.removeClass("lastQkEntry");
    var titulo=trigger.attr('title');
    var newone=$('.lastQkEntry .entryCode').on("keydown", alTeclear);
    $('.lastQkEntry .entryQty').on("keydown", alTeclear);
    newone.focus();
    newone.attr("title",titulo);
    sbmtBtn.prop( "disabled", false );
};

function alPresionar(e){
    if (!e) e= event;
    var keyCode = e.keyCode || e.which;
    var prevent=true;
    switch(keyCode){
        case 119:
            $('#PartSearch').click();
            puedeBuscarPiezas=false;
        break;
        case 27:
            $('#CancelOrder').click();
        break;
        default:
            prevent=false;

    }
    if(prevent){
        if (e.preventDefault) e.preventDefault();
        if (e.stopPropagation) e.stopPropagation();
    }
   return !prevent;
}
function setCambio(){
    var lastTotal=Number($('#LastTotalHdn').val()),
    given=Number($('#AmountGiven').val());
    $('#AmountPaid').val((given>lastTotal?lastTotal:given).toLocaleString('en'));
    $('#AmountPaid').trigger("change");
    $('#AmountReturn').val((given-lastTotal).toLocaleString('en'));
}
function setAmGiDefault(){
    if($('#AmountGiven').val()=="")
        $('#AmountGiven').val("0");
}

$(document).ready(function() {
    $('.lastQkEntry .entryCode').on("keydown", alTeclear);
    $('.lastQkEntry .entryQty').on("keydown", alTeclear);
    $('#AmountGiven').on("keyup", setCambio);
    $('#AmountGiven').on("blur", setAmGiDefault);
    $('#AmountGiven').on("focus", this.select);

    if (document.addEventListener)
    {
       document.addEventListener("keydown",alPresionar,false);
    }
    else if (document.attachEvent)
    {
       document.attachEvent("keydown",alPresionar);
    }
    else
    {
       document.keydown= alPresionar;
    }
});