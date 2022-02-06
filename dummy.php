
<!DOCTYPE html>
<head>
	<title>Buscar órdenes de compra pendientes</title>
	<link rel="icon" href="/erp/favicon.ico" />
	<link rel="stylesheet" href="/erp/css/menu.css" type="text/css" />
	<link rel="stylesheet" href="/erp/css/print.css" media="print" type="text/css" />
	
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	<link rel="stylesheet" href="/erp/css/deno/default.css" media="screen" type="text/css" /><script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script type="text/javascript" src="/erp/javascripts/bootstrap.min.js"></script>
	<script type="text/javascript" src="/erp/javascripts/MiscFunctions.js"></script>
	<script src="https://kit.fontawesome.com/cf48d24055.js" crossorigin="anonymous"></script>
	<meta http-equiv="Content-Type" content="application/html; charset=utf-8; cache-control:no-cache, no-store, must-revalidate; Pragma:no-cache" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<script>
		localStorage.setItem("DateFormat", "d/m/Y");
		localStorage.setItem("Theme", "deno");
	</script>
</head>
<body style="min-width:1px!important;">
	<div id="CanvasDiv" class="container-fluid">
		<input type="hidden" name="Lang" id="Lang" value="US" />
		<div id="HeaderDiv" class="row justify-content-between"><div id="AppInfoDiv" class="col-auto mr-auto"><div id="AppInfoCompanyDiv"><img alt="Compañí­a" src="/erp/css/deno/images/company.png" title="Compañí­a" />&nbsp;xochipili</div><div id="AppInfoUserDiv"><a href="/erp/UserSettings.php">&nbsp;<img alt="Usuario" src="/erp/css/deno/images/user.png" title="Usuario" />&nbsp;Administrador</a></div></div><div id="QuickMenuDiv" class="col-auto">
					<ul id="menu">
						<li><a href="/erp/index.php">Menú Principal</a></li><li><a href="/erp/Dashboard.php">Cuadro de indicadores</a></li><li><a href="/erp/SelectCustomer.php">Clientes</a></li><li><a href="/erp/SelectProduct.php">Artí­culos</a></li><li><a href="/erp/SelectSupplier.php">Proveedores</a></li><li><a href="/erp/ManualContents.php" rel="external" accesskey="8">Manual</a></li><li><a href="/erp/Logout.php" onclick="return confirm('¿Está seguro que desea salir?');">Salir</a></li></ul></div><div id="AppInfoModuleDiv" class="col-12"><a href="/erp/index.php">Compras</a>/Buscar órdenes de compra pendientes</div></div><div id="BodyDiv" class="row"><div id="MessageContainerHead"></div><form action="/erp/PO_SelectOSPurchOrder.php" method="post">
	<div>
	<input type="hidden" name="FormID" value="5205d27701f5185989600f1c8101f894f748f914" /><a href="/erp/PO_Header.php?NewOrder=Yes">Añadir orden de compra</a><p class="page_title_text"><img src="/erp/css/deno/images/magnifier.png" title="Buscar" alt="" /> Buscar órdenes de compra pendientes</p><table class="selection">
			<tr>
				<td>Número de orden: <input type="text" name="OrderNumber" autofocus="autofocus" maxlength="8" size="9" />  En el Almacén:
				<select name="StockLocation"><option selected="selected" value="ALLLOC">Todos</option><option value="PRB">ubiPrueba</option><option value="ZAP" >Zapotitla</option></select> Estado de la Orden: <select name="Status"><option selected="selected" value="Pending_Authorised">Pendiente y Autorizado</option></select>
		Orders Between:&nbsp;
			<input type="text" name="DateFrom" value="29/03/2021"  class="date" size="10" />
		y:&nbsp;
			<input type="text" name="DateTo" value="29/12/2021"  class="date" size="10" />
		<input type="submit" name="SearchOrders" value="Buscar órdenes de compra" />
		</td>
		</tr>
		<tr><td>Mostrar detalles<input type="checkbox" name="PODetails"  /></td></tr>
		</table><br /><div class="page_help_text">Para buscar órdenes de compra de una pieza especí­fica use la herramienta siguiente</div><br />
		<table class="selection">
		<tr><td>Seleccionar una categoría:
		<select name="StockCat"><option value="All">Todos</option><option value="0003">articulos de cocina</option><option value="0002">bebidas</option><option value="666">catPrueba</option><option value="0004">farmacia</option><option value="0005">pan</option><option value="101">prueba</option><option value="0">Sin Categoria</option><option value="0007">untables</option></select></td><td>Indicar una parte del texto de la <b>descripción</b>:</td><td><input type="text" name="Keywords" size="20" maxlength="25" /></td>
		</tr>
		<tr><td></td><td><b>O </b>Introducir una parte del <b>Código de inventario</b>:</td><td><input type="text" name="StockCode" size="15" maxlength="18" /></td>
	</tr>
	</table>
	<br /><table>
		<tr>
			<td><input type="submit" name="SearchParts" value="Buscar Piezas Ahora" />
				<input type="submit" name="ResetPart" value="Limpiar" /></td>
		</tr>
	</table><br /><table cellpadding="2" width="97%" class="selection">
			<thead><tr>
			<th class="ascending">Orden #</th>
			<th class="ascending">Fecha de pedido</th>
			<th class="ascending">Fecha de entrega</th>
			<th class="ascending">Iniciado por</th>
			<th class="ascending">Proveedor</th>
			
			<th class="ascending">Moneda</th><th class="ascending">Total del pedido</th><th class="ascending">Estado</th>
			<th>Imprimir</th>
			<th>Recibir</th>
			</tr>
		</thead>
		<tbody><tr class="striped_row">
			<td><a href="/erp/PO_Header.php?ModifyOrderNumber=15">15</a></td>
			<td>01/09/2021</td>
			<td>02/09/2021</td>
			<td>Administrador</td>
			<td>ibm</td>
			
			<td>MXN</td><td class="number">69.60</td><td>Impreso</td>
				<td><a target="_blank" href="/erp/PO_PDFPurchOrder.php?OrderNo=15&amp;realorderno=&amp;ViewingOnly=2">
				Imprimir Copia</a></td>
				<td><a href="/erp/GoodsReceived.php?PONumber=15">Recibir</a></td>
			</tr><tr class="striped_row">
			<td><a href="/erp/PO_Header.php?ModifyOrderNumber=18">18</a></td>
			<td>21/09/2021</td>
			<td>21/09/2021</td>
			<td>Administrador</td>
			<td>ibm</td>
			
			<td>MXN</td><td class="number">580.00</td><td>Impreso</td>
				<td><a target="_blank" href="/erp/PO_PDFPurchOrder.php?OrderNo=18&amp;realorderno=&amp;ViewingOnly=2">
				Imprimir Copia</a></td>
				<td><a href="/erp/GoodsReceived.php?PONumber=18">Recibir</a></td>
			</tr><tr class="striped_row">
			<td><a href="/erp/PO_Header.php?ModifyOrderNumber=20">20</a></td>
			<td>24/09/2021</td>
			<td>24/09/2021</td>
			<td>Administrador</td>
			<td>ibm</td>
			
			<td>MXN</td><td class="number">0.00</td><td>Impreso</td>
				<td><a target="_blank" href="/erp/PO_PDFPurchOrder.php?OrderNo=20&amp;realorderno=&amp;ViewingOnly=2">
				Imprimir Copia</a></td>
				<td><a href="/erp/GoodsReceived.php?PONumber=20">Recibir</a></td>
			</tr><tr class="striped_row">
			<td><a href="/erp/PO_Header.php?ModifyOrderNumber=21">21</a></td>
			<td>24/09/2021</td>
			<td>24/09/2021</td>
			<td>Administrador</td>
			<td>ibm</td>
			
			<td>MXN</td><td class="number">0.00</td><td>Impreso</td>
				<td><a target="_blank" href="/erp/PO_PDFPurchOrder.php?OrderNo=21&amp;realorderno=&amp;ViewingOnly=2">
				Imprimir Copia</a></td>
				<td><a href="/erp/GoodsReceived.php?PONumber=21">Recibir</a></td>
			</tr><tr class="striped_row">
			<td><a href="/erp/PO_Header.php?ModifyOrderNumber=23">23</a></td>
			<td>27/09/2021</td>
			<td>27/09/2021</td>
			<td>Administrador</td>
			<td>ibm</td>
			
			<td>MXN</td><td class="number">116.00</td><td>Autorizado</td>
				<td><a target="_blank" href="/erp/PO_PDFPurchOrder.php?OrderNo=23">Imprimir</a></td>
				<td></td>
			</tr><tr class="striped_row">
			<td><a href="/erp/PO_Header.php?ModifyOrderNumber=24">24</a></td>
			<td>27/09/2021</td>
			<td>27/09/2021</td>
			<td>Administrador</td>
			<td>ibm</td>
			
			<td>MXN</td><td class="number">69.60</td><td>Autorizado</td>
				<td><a target="_blank" href="/erp/PO_PDFPurchOrder.php?OrderNo=24">Imprimir</a></td>
				<td></td>
			</tr><tr class="striped_row">
			<td><a href="/erp/PO_Header.php?ModifyOrderNumber=27">27</a></td>
			<td>27/09/2021</td>
			<td>27/09/2021</td>
			<td>Administrador</td>
			<td>ibm</td>
			
			<td>MXN</td><td class="number">13.92</td><td>Impreso</td>
				<td><a target="_blank" href="/erp/PO_PDFPurchOrder.php?OrderNo=27&amp;realorderno=&amp;ViewingOnly=2">
				Imprimir Copia</a></td>
				<td><a href="/erp/GoodsReceived.php?PONumber=27">Recibir</a></td>
			</tr><tr class="striped_row">
			<td><a href="/erp/PO_Header.php?ModifyOrderNumber=28">28</a></td>
			<td>14/10/2021</td>
			<td>14/10/2021</td>
			<td>Administrador</td>
			<td>ibm</td>
			
			<td>MXN</td><td class="number">13.92</td><td>Impreso</td>
				<td><a target="_blank" href="/erp/PO_PDFPurchOrder.php?OrderNo=28&amp;realorderno=&amp;ViewingOnly=2">
				Imprimir Copia</a></td>
				<td><a href="/erp/GoodsReceived.php?PONumber=28">Recibir</a></td>
			</tr><tr class="striped_row">
			<td><a href="/erp/PO_Header.php?ModifyOrderNumber=29">29</a></td>
			<td>19/11/2021</td>
			<td>19/11/2021</td>
			<td>Administrador</td>
			<td>ibm</td>
			
			<td>MXN</td><td class="number">89.32</td><td>Impreso</td>
				<td><a target="_blank" href="/erp/PO_PDFPurchOrder.php?OrderNo=29&amp;realorderno=&amp;ViewingOnly=2">
				Imprimir Copia</a></td>
				<td><a href="/erp/GoodsReceived.php?PONumber=29">Recibir</a></td>
			</tr><tr class="striped_row">
			<td><a href="/erp/PO_Header.php?ModifyOrderNumber=30">30</a></td>
			<td>22/11/2021</td>
			<td>22/11/2021</td>
			<td>Administrador</td>
			<td>compaq</td>
			
			<td>MXN</td><td class="number">0.00</td><td>Impreso</td>
				<td><a target="_blank" href="/erp/PO_PDFPurchOrder.php?OrderNo=30&amp;realorderno=&amp;ViewingOnly=2">
				Imprimir Copia</a></td>
				<td><a href="/erp/GoodsReceived.php?PONumber=30">Recibir</a></td>
			</tr><tr class="striped_row">
			<td><a href="/erp/PO_Header.php?ModifyOrderNumber=31">31</a></td>
			<td>13/12/2021</td>
			<td>13/12/2021</td>
			<td>Administrador</td>
			<td>ibm</td>
			
			<td>MXN</td><td class="number">69.60</td><td>Impreso</td>
				<td><a target="_blank" href="/erp/PO_PDFPurchOrder.php?OrderNo=31&amp;realorderno=&amp;ViewingOnly=2">
				Imprimir Copia</a></td>
				<td><a href="/erp/GoodsReceived.php?PONumber=31">Recibir</a></td>
			</tr><tr class="striped_row">
			<td><a href="/erp/PO_Header.php?ModifyOrderNumber=33">33</a></td>
			<td>14/12/2021</td>
			<td>15/12/2021</td>
			<td>Administrador</td>
			<td>ibm</td>
			
			<td>MXN</td><td class="number">41.76</td><td>Impreso</td>
				<td><a target="_blank" href="/erp/PO_PDFPurchOrder.php?OrderNo=33&amp;realorderno=&amp;ViewingOnly=2">
				Imprimir Copia</a></td>
				<td><a href="/erp/GoodsReceived.php?PONumber=33">Recibir</a></td>
			</tr><tr class="striped_row">
			<td><a href="/erp/PO_Header.php?ModifyOrderNumber=34">34</a></td>
			<td>20/12/2021</td>
			<td>20/12/2021</td>
			<td>Administrador</td>
			<td>ibm</td>
			
			<td>MXN</td><td class="number">93.96</td><td>Impreso</td>
				<td><a target="_blank" href="/erp/PO_PDFPurchOrder.php?OrderNo=34&amp;realorderno=&amp;ViewingOnly=2">
				Imprimir Copia</a></td>
				<td><a href="/erp/GoodsReceived.php?PONumber=34">Recibir</a></td>
			</tr><tr class="striped_row">
			<td><a href="/erp/PO_Header.php?ModifyOrderNumber=35">35</a></td>
			<td>20/12/2021</td>
			<td>20/12/2021</td>
			<td>Administrador</td>
			<td>compaq</td>
			
			<td>MXN</td><td class="number">17.40</td><td>Autorizado</td>
				<td><a target="_blank" href="/erp/PO_PDFPurchOrder.php?OrderNo=35">Imprimir</a></td>
				<td></td>
			</tr><tr class="striped_row">
			<td><a href="/erp/PO_Header.php?ModifyOrderNumber=36">36</a></td>
			<td>20/12/2021</td>
			<td>20/12/2021</td>
			<td>Administrador</td>
			<td>ibm</td>
			
			<td>MXN</td><td class="number">230.00</td><td>Impreso</td>
				<td><a target="_blank" href="/erp/PO_PDFPurchOrder.php?OrderNo=36&amp;realorderno=&amp;ViewingOnly=2">
				Imprimir Copia</a></td>
				<td><a href="/erp/GoodsReceived.php?PONumber=36">Recibir</a></td>
			</tr><tr class="striped_row">
			<td><a href="/erp/PO_Header.php?ModifyOrderNumber=37">37</a></td>
			<td>20/12/2021</td>
			<td>20/12/2021</td>
			<td>Administrador</td>
			<td>bimbo</td>
			
			<td>MXN</td><td class="number">269.12</td><td>Impreso</td>
				<td><a target="_blank" href="/erp/PO_PDFPurchOrder.php?OrderNo=37&amp;realorderno=&amp;ViewingOnly=2">
				Imprimir Copia</a></td>
				<td><a href="/erp/GoodsReceived.php?PONumber=37">Recibir</a></td>
			</tr><tr class="striped_row">
			<td><a href="/erp/PO_Header.php?ModifyOrderNumber=39">39</a></td>
			<td>20/12/2021</td>
			<td>20/12/2021</td>
			<td>Administrador</td>
			<td>ibm</td>
			
			<td>MXN</td><td class="number">12.00</td><td>Impreso</td>
				<td><a target="_blank" href="/erp/PO_PDFPurchOrder.php?OrderNo=39&amp;realorderno=&amp;ViewingOnly=2">
				Imprimir Copia</a></td>
				<td><a href="/erp/GoodsReceived.php?PONumber=39">Recibir</a></td>
			</tr><tr class="striped_row">
			<td><a href="/erp/PO_Header.php?ModifyOrderNumber=40">40</a></td>
			<td>20/12/2021</td>
			<td>20/12/2021</td>
			<td>Administrador</td>
			<td>ibm</td>
			
			<td>MXN</td><td class="number">12.00</td><td>Impreso</td>
				<td><a target="_blank" href="/erp/PO_PDFPurchOrder.php?OrderNo=40&amp;realorderno=&amp;ViewingOnly=2">
				Imprimir Copia</a></td>
				<td><a href="/erp/GoodsReceived.php?PONumber=40">Recibir</a></td>
			</tr><tr class="striped_row">
			<td><a href="/erp/PO_Header.php?ModifyOrderNumber=41">41</a></td>
			<td>20/12/2021</td>
			<td>20/12/2021</td>
			<td>Administrador</td>
			<td>ibm</td>
			
			<td>MXN</td><td class="number">10.00</td><td>Impreso</td>
				<td><a target="_blank" href="/erp/PO_PDFPurchOrder.php?OrderNo=41&amp;realorderno=&amp;ViewingOnly=2">
				Imprimir Copia</a></td>
				<td><a href="/erp/GoodsReceived.php?PONumber=41">Recibir</a></td>
			</tr><tr class="striped_row">
			<td><a href="/erp/PO_Header.php?ModifyOrderNumber=42">42</a></td>
			<td>26/12/2021</td>
			<td>26/12/2021</td>
			<td>Administrador</td>
			<td>compaq</td>
			
			<td>MXN</td><td class="number">12.00</td><td>Impreso</td>
				<td><a target="_blank" href="/erp/PO_PDFPurchOrder.php?OrderNo=42&amp;realorderno=&amp;ViewingOnly=2">
				Imprimir Copia</a></td>
				<td><a href="/erp/GoodsReceived.php?PONumber=42">Recibir</a></td>
			</tr><tr class="striped_row">
			<td><a href="/erp/PO_Header.php?ModifyOrderNumber=44">44</a></td>
			<td>29/12/2021</td>
			<td>29/12/2021</td>
			<td>Administrador</td>
			<td>bimbo</td>
			
			<td>MXN</td><td class="number">50.00</td><td>Impreso</td>
				<td><a target="_blank" href="/erp/PO_PDFPurchOrder.php?OrderNo=44&amp;realorderno=&amp;ViewingOnly=2">
				Imprimir Copia</a></td>
				<td><a href="/erp/GoodsReceived.php?PONumber=44">Recibir</a></td>
			</tr></tbody></table></div>
      </form><div id="MessageContainerFoot"></div></div><div class="centre noprint"><form action="/erp/PO_SelectOSPurchOrder.php" method="post"><input name="FormID" type="hidden" value="5205d27701f5185989600f1c8101f894f748f914" /><input name="ScriptName" type="hidden" value="" /><input name="Title" type="hidden" value="Buscar órdenes de compra pendientes" /></form></div><div id="FooterDiv"><div id="FooterWrapDiv"><div id="FooterLogoDiv"><img alt="webERP" src="/erp/companies/koinobor_webe578/logo.jpg" title="webERP Copyright &copy; weberp.org - 2022" width="120" /></div><div id="FooterTimeDiv">Miércoles 2 Febrero 2022 19:35</div><div id="FooterVersionDiv">webERP versión 4.15.1 Copyright &copy; 2004 - 2022 <a href="http://www.weberp.org/weberp/doc/Manual/ManualContributors.html" target="_blank">weberp.org</a></div></div></div><!--END div id="FooterDiv"--></div><!--div id="CanvasDiv"--></body><script>
	if ( window.history.replaceState ) {
	  window.history.replaceState( null, null, window.location.href );
	}
	</script></html>