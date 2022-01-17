
 	<style type="text/css">
		#invoice-POS{
  padding:2mm;
  margin: 0 auto;
  width: 100%;
  background: #FFF;
    font-size: 9vw;

        }
h1{
  font-size: 1.5em;
  color: #222;
}
h2{font-size: .9em;}
h3{
  font-size: 1.2em;
  font-weight: 300;
  line-height: 2em;
}
p{
  font-size: .7em;
  color: #666;
  line-height: 1.2em;
}
 
#top, #mid,#bot{ /* Targets all id with 'col-' */
  border-bottom: 1px solid #EEE;
}

#top{min-height: 100px;}
#mid{min-height: 80px;} 
#bot{ min-height: 50px;}

#top .logo{
  //float: left;
	height: 40vw;
	width: 40vw;
	background: url(<?=$RootPath. '/css/'. $_SESSION['Theme']. '/images/company.png'?>) no-repeat;
	background-size: 40vw 40vw;
}
.clientlogo{
  float: left;
	height: 40vw;
	width: 40vw;
	background: url(<?=$RootPath. '/css/'. $_SESSION['Theme']. '/images/company.png'?>) no-repeat;
	background-size: 40vw 40vw;
  border-radius: 50px;
}
.info{
  display: block;
  //float:left;
  margin-left: 0;
}
.title{
  float: right;
}
.title p{text-align: right;} 
table{
  width: 100%;
  border-collapse: collapse;
}
td{
  //padding: 5px 0 5px 15px;
  //border: 1px solid #EEE
}
.tabletitle{
  //padding: 5px;
  font-size: .5em;
  background: #EEE;
}
.service{border-bottom: 1px solid #EEE;}
.item{width: 24mm;}
.itemtext{font-size: .5em;}

#legalcopy{
  margin-top: 5mm;
}
	</style>
 <div id="invoice-POS" class="onlyprint">
    
    <center id="top">
      <div class="logo"></div>
      <div class="info"> 
        <h2><?=stripslashes($_SESSION['CompanyRecord']['coyname'])?></h2>
      </div><!--End Info-->
    </center><!--End InvoiceTop-->
    
    <div id="mid">
      <div class="info">
        <h2><?=_('Contact')?></h2>
        <p> 
            <?=_('Address')?> : <?=$_SESSION['CompanyRecord']['regOffice1']?></br>
            <?=_('Email')?>   : <?=$_SESSION['CompanyRecord']['email']?></br>
            <?=_('Phone')?>   : <?=$_SESSION['CompanyRecord']['telephone']?></br>
        </p>
      </div>
    </div><!--End Invoice Mid-->
    
    <div id="bot">

      <div id="table">
        <table>
          <caption>Venta</caption>
          <tr class="tabletitle">
            <td class="item"><h2><?=_('Item')?></h2></td>
            <td class="Hours"><h2><?=_('U/P')?></h2></td>
            <td class="Hours"><h2><?=_('Qty')?></h2></td>
            <td class="Rate"><h2><?=_('Sub Total')?></h2></td>
          </tr>
                        <?php 
                        $currencyDecimal=$_SESSION['Items'.$identifier]->CurrDecimalPlaces;
                        $TotalTaxes=$TotalPrice=0;
                        foreach ($_SESSION['Items'.$identifier]->LineItems as $value) {
                          $unitTax=$value->Price*$value->Taxes[1]->TaxRate;
                          $itemTaxes=$unitTax*$value->Quantity;
                          $unitPrice=$value->Price+$unitTax;
                          $itemPrice=$unitPrice*$value->Quantity;
                          $TotalTaxes+=$itemTaxes;
                          $TotalPrice+=$itemPrice;
                        ?>
          <tr class="service">
            <td class="tableitem"><p class="itemtext"><?=$value->ItemDescription?></p></td>
            <td class="tableitem"><p class="itemtext"><?=round($unitPrice,$currencyDecimal)  ?></p></td>
            <td class="tableitem"><p class="itemtext"><?=round($value->Quantity,$value->DecimalPlaces)?></p></td>
            <td class="tableitem"><p class="itemtext"><?=round($itemPrice,$currencyDecimal)?></p></td>
          </tr>
                        <?php
                        } 
                        ?>
          <tr class="tabletitle">
            <td></td>
            <td class="Rate"><h2><?=_('Inc tax')?></h2></td>
            <td class="payment"><h2><?=round($TotalTaxes,$currencyDecimal)?></h2></td>
          </tr>


							<tr class="tabletitle">
								<td></td>
								<td class="Rate"><h2>Total</h2></td>
								<td class="payment"><h2><?=$TotalPrice?></h2></td>
							</tr>

						</table>
					</div><!--End Table-->

					<div id="legalcopy">
						<p class="legal"><strong>Gracias por su compra</strong> Esta nota no es una factura de compra. 
						</p>
					</div>

				</div><!--End InvoiceBot-->
  </div><!--End Invoice-->


 