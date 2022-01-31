<?php

require_once($PathPrefix .'includes/devstar/novatech.php');
$coyr=$_SESSION['CompanyRecord'];
$paymentAdjustment=locale_number_format(
  (getPaymentAdjunstment($_POST['PaymentMethod'])/-100) * ($subTotalTax)
  ,$currDecimals);
?>
 	<style type="text/css">
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
background: url(<?=$RootPath. '/companies/'. $_SESSION['DatabaseName']. '/logoBN.jpg'?>) no-repeat;
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
        <h2><?=stripslashes($coyr['coyname'])?></h2>
      </div><!--End Info-->
    </center><!--End InvoiceTop-->
    
    <div id="mid">
      <div class="info">
        <p> 
            <?=_('Address')?> : <?=implode_list($coyr['regoffice1'],$coyr['regoffice2'],$coyr['regoffice3'],$coyr['regoffice4'],$coyr['regoffice5'],$coyr['regoffice6'])?></br>
        </p>
      </div>
    </div><!--End Invoice Mid-->
    
    <div id="bot">

      <div id="table">
        <table>
          <caption><h2><?=_('Sale')?><h2></caption>
          <tr class="tabletitle">
            <td class="item"><h2><?=_('Item')?></h2></td>
            <td class="Hours"><h2><?=_('U/P')?></h2></td>
            <td class="Hours"><h2><?=_('Qty')?></h2></td>
            <td class="Rate"><h2><?=_('Sub Total')?></h2></td>
          </tr>
                        <?php 
                        $currencyDecimal=$_SESSION['Items'.$identifier]->CurrDecimalPlaces;
                        $TotalTaxes=0;
                        $TotalPrice=$paymentAdjustment;
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
            <td class="tableitem"><p class="itemtext payment"><?=locale_number_format($unitPrice ,$currencyDecimal)  ?></p></td>
            <td class="tableitem"><p class="itemtext payment"><?=locale_number_format($value->Quantity,$value->DecimalPlaces)?></p></td>
            <td class="tableitem"><p class="itemtext payment"><?=locale_number_format($itemPrice,$currencyDecimal)?></p></td>
          </tr>
                        <?php
                        } 
                        ?>
                        
      <tfoot>
          <tr class="tabletitle">
            <td></td>
            <td colspan="2"  class="Rate"><h2><?=_('Inc tax')?></h2></td>
            <td class="payment"><h2><?=locale_number_format($TotalTaxes,$currencyDecimal)?></h2></td>
          </tr>
          <?php
          if($paymentAdjustment!=0){
          ?>
          <tr class="tabletitle">
            <td></td>
            <td colspan="2"  class="Rate"><h2><?=_('Adjustment')?></h2></td>
            <td class="payment"><h2><?=locale_number_format($paymentAdjustment,$currencyDecimal)?></h2></td>
          </tr>
          <?php
          }
          ?>
          <tr class="tabletitle">
            <td></td>
            <td colspan="2"  class="Rate"><h2><?=_("TOTAL")?></h2></td>
            <td class="payment"><h2><?=locale_number_format($TotalPrice,$currencyDecimal)?></h2></td>
          </tr>
          </tfoot>
        </table>
        <div id="pFoot">
            <div>
            <strong><?=_("Payment Type")?></strong>: <span><?=_(getPaymentName($_POST['PaymentMethod']))?></span>
            </div>
            <div>
            <strong><?=_("Sales person")?></strong>: <span><?=getSalesmanName($_SESSION['Items'.$identifier]->SalesPerson)?></span>
            </div>
            <div>
            <strong><?=_("Branch")?></strong>: <span><?=$_SESSION['Items'.$identifier]->Branch?></span>
            </div>
            <div>
            <strong><?=_("Ticket")?></strong>: <span><?=$InvoiceNo?></span>
            </div>
            <div>
            <strong><?=_("Date")?></strong>: <span><?=date('d/m/Y H:i',$ticketTime)?></span>
            </div>
          </div>

      </div><!--End Table-->

      <div id="legalcopy">
        <p class="legal"><strong>¡Gracias por su compra!</strong> Esta es una nota sin valor fiscal. 
        </p>
      </div>

    </div><!--End InvoiceBot-->
  </div><!--End Invoice-->


 