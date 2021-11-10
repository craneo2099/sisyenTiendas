<?php
// Stocks.php
// Defines an  item - maintenance and addition of new parts.
include('../includes/session.php');
include('../includes/devstar/stocks.inc');
include_once('../includes/devstar/prices.inc');
$Title = _('Add multiple new items');
$ViewTopic = 'Inventory';
$BookMark = 'InventoryAddingItems';
$scriptList='/javascripts/Stock/MultiStocks.js';

include('../includes/header.php');
include_once('../includes/SQL_CommonFunctions.inc');

$msgList=array();
if($_POST){
	if($_POST["accion"]=="guardar"){
		$precio= array();
		$precio['CurrAbrev']=$_SESSION['CompanyRecord']['currencydefault'];
		$precio['TypeAbbrev']=$_SESSION['DefaultPriceList'];
		$precio['StartDate']=Date($_SESSION['DefaultDateFormat']);
		$precio['EndDate']='';
		$_POST['PriceDefaults']=$precio;
		$msgList=saveStockItems($_POST);
	}
}
$categories=getCategories();
$UOMrows=getMeasureUnits();
$loRows=getLocations();
$showStocks=count($loRows)==1?1:false;
$stockItemUrl=$RootPath.'/Stock/StockItem.php'



?>
<div class="col-12">
<p class="page_title_text"><img alt="" src="<?=$RootPath?>/css/<?=$Theme?>/images/inventory.png"
	title="<?=$Title?>" /> <?=$Title?></p>
	<div class="selection">
		<div id="stockMsg">
	<?PHP
	foreach ($msgList as $stockMsg) {
		$typeMsg=$stockMsg['type'];
		$textMsg=$stockMsg['text'];
		$idStockMsg=$stockMsg['id'];
	?>
		<div class="alert alert-<?=$typeMsg?> alert-dismissible fade show noprint" role="alert">
			<a href="<?=$RootPath?>/SelectProduct.php?StockID=<?=$idStockMsg?>" class="alert-link"><?=$idStockMsg?>
			</a> <?=$textMsg?>
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>
	<?PHP
	}
	?>
		</div>
		<form action="<?=$_SERVER['PHP_SELF'];?>" method="POST" accept-charset="utf-8" class="form_of">
			<input type="Hidden" name="FormID" value="<?=$_SESSION['FormID']?>" />
			<input type="Hidden" name="lastIdx" id="lastIdx" value="0" />
			<input type="Hidden" name="accion" id="accion" value="guardar" />
			<div class="scroller">
			<div class="holder">
			<table class="table table-hover table-sm min-w-100">
				<caption><?= _('Fast item capture form')?></caption>
				<thead>
					<tr>

						<th  scope="col-3"><?=_('Item Code')?></th>
						<th  scope="col"><?=_('Part Description')?></th>
						<th  scope="col"><?= _('Part Description'). ' (' . _('long') . ')'?></th>
						<th  scope="col"><?=_('Category')?></th>
						<th  scope="col"><?=_('Units of Measure')?></th>
						<th  scope="col-3"><?=_('Assembly, Kit, Manufactured or Service/Labour')?></th>
						<th  scope="col"><?=_('Perishable')?></th>
						<th  scope="col"><?=_('Packaged Volume (metres cubed)')?></th>
						<th  scope="col"><?=_('Net Weight (KGs)')?></th>
						<th  scope="col"><?=_('Bar Code')?></th>
						<?php if($showStocks){?><th  scope="col"><?=_('Stock')?></th><?php }?>
						<th  scope="col"><?=_('Price')?></th>
						<th  scope="col"><?=_('Cost')?></th>
						<th  scope="col"><?=_('Remove')?></th>
					</tr>
				</thead>
				<tbody>
					<tr id="cursorRow">
						<td>
							<div class="form-group">
								<input type="text" class="form-control form-control-sm campo requerido" id="StockID" name="StockID" autofocus>
							</div>
						</td>
						<td>
							<div class="form-group">
								<input type="text" class="form-control form-control-sm campo requerido" id="Description" name="Description">
							</div>
						</td>
						<td>
							<div class="form-group">
								<input type="text" class="form-control form-control-sm campo" id="LongDescription" name="LongDescription">
							</div>
						</td>
						<td>
							<div class="form-group">
							<select class="form-control form-control-sm campo requerido" id="CategoryID" name="CategoryID" >
								<?PHP
								foreach ($categories as $categoryOpt) {
									?>
								<option value="<?=$categoryOpt['categoryid']?>"><?=$categoryOpt['categorydescription']?></option>
								<?php 
								}
								?>
							</select>
							</div>
						</td>
						<td>
							<div class="form-group">
								<select class="form-control form-control-sm campo"
									id="Units" name="Units">
								<?PHP
								foreach ($UOMrows as $UOMrow) {
								?>
								<option value="<?=$UOMrow['unitname']?>"><?=_($UOMrow['unitname'])?></option>
								<?php 
								}
								?>
								</select>
							</div>
						</td>
						<td>
							<div class="form-group">
								<select class="form-control form-control-sm campo" id="MBFlag" name="MBFlag" >
								<option value="A"><?= _('Assembly') ?></option>
								<option value="K"><?= _('Kit') ?></option>
								<option value="M"><?= _('Manufactured') ?></option>
								<option value="G"><?= _('Phantom') ?></option>
								<option value="B" selected="selected" ><?= _('Purchased') ?></option>
								<option value="D"><?= _('Service/Labour') ?></option>
							</select>
							</div>
						</td>
						<td>
							<div class="form-group">
								
								<select  class="form-control form-control-sm campo" id="Perishable" name="Perishable">
									<option value="0"><?= _('No')?></option>
									<option value="1"><?= _('Yes')?></option>
								</select>
							</div>
						</td>
						<td>
							<div class="form-group">
								<input type="text" class="form-control form-control-sm number campo"
									id="Volume" name="Volume"  placeholder="0.0"
									size="12" maxlength="10" value="0.0">
							</div>
						</td>
						<td>
							<div class="form-group">
								<input type="text" class="form-control form-control-sm number campo"
									id="NetWeight" name="NetWeight"  placeholder="0.0"
									size="12" maxlength="10" value="0.0">
							</div>
						</td>
						<td>
							<div class="form-group">
								<input type="text" class="form-control form-control-sm campo" id="BarCode" name="BarCode">
							</div>
						</td>
						<?php if($showStocks){?><td>
							<div class="form-group">
								<input type="text" class="form-control form-control-sm number campo"
									id="noStock" name="noStock" placeholder="0"
									size="2" maxlength="4" value="0">
							</div>
						</td><?php }?>
						<td>
							<div class="form-group">
								<input type="text" class="form-control form-control-sm number campo" 
								id="precio" name="precio" placeholder="0.0"
								size="6" maxlength="6" value="0.0">
							</div>
						</td>
						<td>
							<div class="form-group">
								<input type="text" class="form-control form-control-sm number campo" 
								id="costo" name="costo" placeholder="0.0"
								size="6" maxlength="6" value="0.0">
							</div>
						</td>
						
						<td class="align-middle">
							<div class="form-group">
								<a href="#" id="delBtn" class="campo hidden cellIcon"  tabindex="-1">
									<i class="fas fa-backspace"></i></a>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
			</div>
			</div>
			<div class="d-flex flex-row-reverse">
			  <div class="p-2"><input class="btn btn-primary" type="submit" value="<?=_('Save')?>"></div>
			</div>
		</form>
	</div>
</div>
<script>
	var rowIdx=0;

function deleteme(ev){
	var me=ev.data['me'];
	$("#"+me).remove();
}
function getStockItem(barCodeIn) {
	var iden=$('#StockID');
	iden.val("");
	$.ajax({
		url:'<?=$stockItemUrl?>',
		type:'GET',
		data:{barCode:barCodeIn},
		success:function (goten){
			var iden=$('#StockID'),rowname="Row"+rowIdx;
			var trigger=$('#cursorRow'),newRow=$("<tr id='"+rowname+"'></tr>").html(trigger.html());
			if(goten.response==='EXISTE'){
				printMensaje('<?=_('The Stock Item code must be unique. Please re-enter a unique Stock Item code.')?>',
				'danger','<?=$RootPath?>/SelectProduct.php?StockID='+barCodeIn,barCodeIn)
				return;
			}
			if(goten.response!=true){

				return;
			}
			var resultado=goten.result,valor="",focuseable;
			newRow.find(".campo").each(function (idx){
				jqObj=$(this);
				key=jqObj.attr("id");
				var newid=key+rowIdx;
				valor=resultado[key];
				jqObj.val(valor);
				if(key=='delBtn'){
					jqObj.on("click",{me:rowname},deleteme)
				}else if(key=='Description'){
					if(!jqObj.val()){
						focuseable=jqObj;
					}else{
						focuseable=iden;
					}
				}
				if(jqObj.hasClass('requerido')){
					jqObj.removeClass('requerido');
					jqObj.attr("required", true);
				}
				if(jqObj.is("select") &&valor){
					jqObj.find("option[value='"+valor+"']").attr('selected','selected');
				}
				jqObj.attr("title",valor);
				jqObj.attr("id",newid);
				jqObj.attr("name",newid);
			});
			newRow.find(".hidden").removeClass('hidden');
			trigger.before(newRow);
			focuseable.focus();
			rowIdx++;
			$('#lastIdx').val(rowIdx);
		}
			
	});
}


function alTeclear(event) {
    var keyCode = event.keyCode || event.which,iden=$('#StockID'); 
    if ( keyCode != 9 || !iden.val()) {
        return true;
    }
    event.preventDefault();

	getStockItem(iden.val());


}
function printMensaje(mensaje,tipo,link,msgLink){
	var mensajeHTML=`<div class="alert alert-${tipo} alert-dismissible fade show noprint" role="alert">
			<a href="${link}" class="alert-link">${msgLink}
			</a> ${mensaje}
			<button type="button" class="close" data-dismiss="alert" aria-label="Close">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>`;
	$('#stockMsg').html(mensajeHTML);
}
$(document).ready(function() {
	$('#StockID').on("keydown", alTeclear);
});

</script>
<?php

include($PathPrefix.'includes/footer.php');
?>
