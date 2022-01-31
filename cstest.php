<html><head>
	<title>Ventas de mostrador</title>
	<link rel="icon" href="/SISYENTIENDAS/favicon.ico">
	<link rel="stylesheet" href="/SISYENTIENDAS/css/menu.css" type="text/css">
	<link rel="stylesheet" href="/SISYENTIENDAS/css/print.css" media="print" type="text/css">
	
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
	<link rel="stylesheet" href="/SISYENTIENDAS/css/deno/default.css" media="screen" type="text/css">
            <link rel="stylesheet" type="text/css" href="/SISYENTIENDAS/css/deno/css/datasheets.css"><script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<script type="text/javascript" src="/SISYENTIENDAS/javascripts/bootstrap.min.js"></script>
	<script type="text/javascript" src="/SISYENTIENDAS/javascripts/MiscFunctions.js"></script>
	<script src="https://kit.fontawesome.com/cf48d24055.js" crossorigin="anonymous"></script>
<meta http-equiv="Content-Type" content="application/html; charset=utf-8; cache-control:no-cache, no-store, must-revalidate; Pragma:no-cache">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<script>
		localStorage.setItem("DateFormat", "d/m/Y");
		localStorage.setItem("Theme", "deno");
	</script>
</head>
<body  style="min-width:1px!important;">
	<div id="CanvasDiv" class="container-fluid">
		<input type="hidden" name="Lang" id="Lang" value="US">
		<div id="HeaderDiv" class="row justify-content-between"><div id="AppInfoDiv" class="col-auto mr-auto"><div id="AppInfoCompanyDiv"><img alt="Compañí­a" src="/SISYENTIENDAS/css/deno/images/company.png" title="Compañí­a">&nbsp;xochipili</div><div id="AppInfoUserDiv"><a href="/SISYENTIENDAS/UserSettings.php">&nbsp;<img alt="Usuario" src="/SISYENTIENDAS/css/deno/images/user.png" title="Usuario">&nbsp;Demonstration user</a></div></div><div id="QuickMenuDiv" class="col-auto">
					<ul id="menu">
						<li><a href="/SISYENTIENDAS/index.php">Menú Principal</a></li><li><a href="/SISYENTIENDAS/Dashboard.php">Cuadro de indicadores</a></li><li><a href="/SISYENTIENDAS/SelectCustomer.php">Clientes</a></li><li><a href="/SISYENTIENDAS/SelectProduct.php">Artí­culos</a></li><li><a href="/SISYENTIENDAS/SelectSupplier.php">Proveedores</a></li><li><a href="/SISYENTIENDAS/ManualContents.php?ViewTopic=SalesOrders#SalesOrderCounterSales" rel="external" accesskey="8">Manual</a></li><li><a href="/SISYENTIENDAS/Logout.php" onclick="return confirm('¿Está seguro que desea salir?');">Salir</a></li></ul></div><div id="AppInfoModuleDiv" class="col-12"><a href="/SISYENTIENDAS/index.php">Ventas</a>/Ventas de mostrador</div></div><div id="BodyDiv" class="row"><div id="MessageContainerHead"></div><form action="/SISYENTIENDAS/CounterSales.php?identifier=1643150286" id="SelectParts" class="" method="post">
<input type="hidden" name="FormID" value="bbec25c1106f0169c666db48c37c55165836fe25"><div><br>
		<table width="90%" cellpadding="2" class="noprint">
		<tbody><tr style="tableheader"><th>Código del artí­culo</th>
   	      <th>Descripción del artículo</th>
	      <th>Cantidad</th>
	      <th>Existencia</th>
	      <th>Unidad</th>
	      <th>Precio</th><th>Descuento</th>
			  <th>% GB</th><th>Neto</th>
	      <th>Impuestos</th>
	      <th>Total<br>Con impuesto</th>
	      </tr><tr class="striped_row">		<td><input type="hidden" name="POLine_0" value="">
			<input type="hidden" name="ItemDue_0" value="26/01/2022">
			<a target="_blank" href="/SISYENTIENDAS/StockStatus.php?identifier=1643150286&amp;StockID=725226066660&amp;DebtorNo=1">725226066660</a>
		</td>
		<td title="Pulparindo Dulces de la RosaDulce de tamarindo salado y enchilado">
							<a target="_blank" href="/SISYENTIENDAS/SelectProduct.php?StockID=725226066660">Pulparindo Dulces de la Rosa</a>
						</td>
		<td><input class="number" tabindex="1" type="text" name="Quantity_0" required="required" onchange="$('#ProcessSale').attr('disabled', true);" size="6" maxlength="6" value="1">
</td>
			<td class="number">116</td>
			<td>c/u</td>			<td>
				<input id="hidPriceNoRound" type="hidden" name="Price_0" value="6.8970">
				<input class="number" type="text" name="viewPrice_0" required="required" size="16" maxlength="16" value="6.90" onchange="
						$('#hidPriceNoRound').val(this.value).trigger('change');
						$('#ProcessSale').attr('disabled', true);

					"></td>
				<td>
					<input class="number" type="text" name="Discount_0" required="required" size="5" maxlength="4" onchange="$('#ProcessSale').attr('disabled', true);" value="0.00"></td>
				<td>
					<input class="number" type="text" name="GPPercent_0" required="required" size="3" maxlength="40" onchange="$('#ProcessSale').attr('disabled', true);" value="13.01"></td>
				<td class="number">6.90</td><td class="number">1.10</td><td class="number">8.00</td><td><a href="/SISYENTIENDAS/CounterSales.php?identifier=1643150286&amp;Delete=0" onclick="return confirm('¿Está seguro?');">Suprimir</a></td></tr><input type="hidden" name="Narrative" value="">	<tr class="striped_row">
		<td colspan="8" class="number"><b>Total</b></td>
		<td class="number">6.90</td>
		<td class="number">1.10</td>
		<td id="total" class="number">8.00</td>
		<td></td>
	</tr>
	</tbody></table>
	<input type="hidden" name="TaxTotal" value="1.10352">
	<input type="hidden" name="LastTotalHdn" id="LastTotalHdn" value="8.00">
	<input type="hidden" name="hdnAdjustment" id="hdnAdjustment" value="0.00">
<table class="noprint"><tbody><tr><td><table><tbody><tr>
			<td>Vendedor:</td><td><select name="SalesPerson"><option value="001">Ale</option><option selected="selected" value="002">laura</option><option value="003">nacidomorir</option><option value="004">mortal</option><option value="005">mucho</option><option value="006">muchomas</option><option value="007">ventas1</option></select></td></tr><tr>
			<td>Comentarios:</td>
			<td><textarea name="Comments" cols="23" rows="5"></textarea></td>
		</tr></tbody></table></td><th valign="bottom"><table class="selection">	
	<tbody><tr>
		<td>Tipo de pago:</td>
		<td>
			<select name="PaymentMethod" onchange="$('#ProcessSale').attr('disabled', true);"><option value="6">American expres</option><option selected="selected" value="2">Efectivo</option><option value="4">Tarjeta de Cred</option><option value="5">Tarjeta de Debi</option></select>		</td>
	</tr>
<tr>
			<td>Cuenta bancaria:</td>
			<td><select name="BankAccount"><option selected="selected" value="1020">Global - MXN</option><option value="1440">prueba - MXN</option></select></td>
		</tr>			<tr>
					<td>Monto Recibido:</td>
					<td><input type="text" class="number" name="AmountGiven" id="AmountGiven" required="required" title="Enter the amount given by the customer, this must greater the amount of the sale" maxlength="12" size="12" value="10" autofocus=""></td>
				</tr>
				
			<tr>
				<td>Monto pagado:</td>
				<td><input type="text" class="number" name="AmountPaid" id="AmountPaid" required="required" readonly="" title="Anote la cantidad pagada por el cliente, esta debe ser igual al monto de la venta" maxlength="12" size="12" value="8.00"></td>
			</tr>
				
			<tr>
				<td>Cambio:</td>
				<td><input type="text" class="number" name="AmountReturn" id="AmountReturn" disabled="" title="This is the amount to return to client." maxlength="12" size="12" value="2"></td>
			</tr>


		</tbody></table>
		</th>
	</tr>
	</tbody></table>
	<div class="centre"><style type="text/css">
        @media print{
		#invoice-POS{

  padding:0mm 5mm;
  margin: 10 auto;
  width: 90vw;
    font-size: 5vw;
          color:#000;
font-family: "Lucida Console", Courier, monospace;
        }
h2{
	margin: 4vw auto;
	font-size: .9em;
          font-weight: bold;}

p{
  line-height: 1.2em;
  margin-bottom: 1vw;
}
 
#top, #mid{ 
  border-bottom: 2px solid #000;
  
}
#mid,#pFoot,#legalcopy{
    font-size: .7em;
  
}
#pFoot{
    text-align: left;
    margin-top: 4vw;
}

#pFoot div{
    float: left;
    margin-right: 5vw;
}

#top{
  width: 100%;
  min-height: 100px;}
#mid{min-height: 80px;} 
#bot{ min-height: 50px;	
    width: 100%;

}

#top .logo{
	height: 21vw;
	width: 100%;
	background: url(/SISYENTIENDAS/companies/koinobor_webe578/logoBN.jpg) no-repeat;
	background-size: 82vw 21vw;
}
.info{
  display: block;
  text-align: center;
  margin-left: 0;
    width: 100%;
}
.title{
  float: right;
}
.title p{text-align: right;} 
table{
  width: 100%;
  border-collapse: collapse;
  font-size: 4vw;
}
tbody{
  border-bottom: 2px solid #000;
}
table h2{
    margin: 1vw auto;
}
table caption{
  text-align: center;
  caption-side:top;
  color:#000;
  margin-bottom: 0px;
  padding-bottom: 1px;
}


.tabletitle{
          color:#000;

}
.service{text-align: left;}
.item{width: 40vw;}
.payment{
  text-align: right;}
.itemtext{font-size: .9em;
          color:#000;
}

#legalcopy{
  margin-top: 2vw;
  float: left;
}
}
	</style>

 <div id="invoice-POS" class="onlyprint">
    
    <center id="top">
      <div class="logo"></div>
      <div class="info"> 
        <h2>xochipili</h2>
      </div><!--End Info-->
    </center><!--End InvoiceTop-->
    
    <div id="mid">
      <div class="info">
            Dirección : Calle falsa mz 13 lt 9 del Milpa alta Col. Mediterraneo CP. 17203 Ciudad de México<br>
      </div>
    </div><!--End Invoice Mid-->
    
    <div id="bot">

      <div id="table">
        <table>
          <caption>
            <h2>Venta</h2></caption>
              <tbody><tr class="tabletitle">
                <th class="item"><h2>Articulo</h2></th>
                <th class="Hours"><h2>P.U.</h2></th>
                <th class="Hours"><h2>Cant.</h2></th>
                <th class="Rate"><h2>Sub Total</h2></th>
              </tr>
                                      <tr class="service">
                <td class="tableitem"><p class="itemtext">Pulparindo Dulces de la Rosa</p></td>
                <td class="tableitem"><p class="itemtext payment">8.00</p></td>
                <td class="tableitem"><p class="itemtext payment">1</p></td>
                <td class="tableitem"><p class="itemtext payment">8.00</p></td>
              </tr>
                                      <tr class="service">
                <td class="tableitem"><p class="itemtext">Canelitas</p></td>
                <td class="tableitem"><p class="itemtext payment">8.00</p></td>
                <td class="tableitem"><p class="itemtext payment">1</p></td>
                <td class="tableitem"><p class="itemtext payment">8.00</p></td>
              </tr>
                                      <tr class="service">
                <td class="tableitem"><p class="itemtext">Nachos</p></td>
                <td class="tableitem"><p class="itemtext payment">18.00</p></td>
                <td class="tableitem"><p class="itemtext payment">1</p></td>
                <td class="tableitem"><p class="itemtext payment">18.00</p></td>
              </tr>
          </tbody>
          <tfoot>
               <tr class="tabletitle">
                <td></td>
                <td colspan="2" class="Rate"><h2>IVA inc.</h2></td>
                <td class="payment"><h2>1.1</h2></td>
              </tr>

              <tr class="tabletitle">
                <td></td>
                <td colspan="2" class="Rate"><h2>Total</h2></td>
                <td class="payment"><h2>8.00</h2></td>
              </tr>
          </tfoot>
        </table>

          </div>
        <div id="pFoot">
            <div>
              <strong>Tipo de pago:</strong> <span>Efectivo</span>
            </div>
            <div>
            <strong>Vendedor:</strong> <span>laura</span>
            </div>
            <div>
            <strong>Sucursal:</strong> <span>1</span>
            </div>
            <div>
            <strong>Ticket:</strong> <span>66</span>
            </div>
            <div>
            <strong>Fecha:</strong> <span>26/01/2022 17:32</span>
            </div>
      </div><!--End Table-->

      <div id="legalcopy">
        <p class="legal"><strong>¡Gracias por su compra!</strong>&nbsp;Esta es una nota sin valor fiscal. 
        </p>
      </div>

    </div><!--End InvoiceBot-->
  </div><!--End Invoice-->


 
		
		<input type="submit" name="NewOrder" id="NewOrder" class="noprint" value="Iniciar una nueva venta de mostrador"></div>
    
            <script type="text/javascript" src="/SISYENTIENDAS/javascripts/CounterSales.js"></script><div id="MessageContainerFoot"><div class="Message success noprint"><span class="MessageCloseButton">×</span><b>CORRECTO Informe</b> : Número de orden 69 ha sido creada</div><div class="Message success noprint"><span class="MessageCloseButton">×</span><b>CORRECTO Informe</b> : Número de ticket 66 procesado</div></div></div><div class="centre noprint"><input name="FormID" type="hidden" value="bbec25c1106f0169c666db48c37c55165836fe25"><input name="ScriptName" type="hidden" value=""><input name="Title" type="hidden" value="Ventas de mostrador"></div></form><div id="FooterDiv"><div id="FooterWrapDiv"><div id="FooterLogoDiv"><img alt="webERP" src="/SISYENTIENDAS/companies/koinobor_webe578/logo.jpg" title="webERP Copyright © weberp.org - 2022" width="120"></div><div id="FooterTimeDiv">Miércoles 26 Enero 2022 17:32</div><div id="FooterVersionDiv">webERP versión 4.15.1 Copyright © 2004 - 2022 <a href="http://www.weberp.org/weberp/doc/Manual/ManualContributors.html" target="_blank">weberp.org</a></div></div></div><!--END div id="FooterDiv"--></div><!--div id="CanvasDiv"--><script>
	if ( window.history.replaceState ) {
	  window.history.replaceState( null, null, window.location.href );
	}
	</script></div></body></html></strong>