<?php


include('includes/session.php');
include('includes/SQL_CommonFunctions.inc');

if (isset($_GET['OrderNo'])) {
	$Title = _('Reviewing Purchase Order Number').' ' . $_GET['OrderNo'];
	$_GET['OrderNo']=(int)$_GET['OrderNo'];
} else {
	$Title = _('Reviewing A Purchase Order');
}
include('includes/header.php');

if (isset($_GET['FromGRNNo'])){

	$SQL= "SELECT purchorderdetails.orderno
			FROM purchorderdetails INNER JOIN grns
			ON purchorderdetails.podetailitem=grns.podetailitem
			WHERE grns.grnno='" . $_GET['FromGRNNo'] ."'";

	$ErrMsg = _('The search of the GRNs was unsuccessful') . ' - ' . _('the SQL statement returned the error');
	$OrderResult = DB_query($SQL, $ErrMsg);

	$OrderRow = DB_fetch_row($OrderResult);
	$_GET['OrderNo'] = $OrderRow[0];
	echo '<br /><h3>' . _('Order Number') . ' ' . $_GET['OrderNo'] . '</h3>';
}

if (!isset($_GET['OrderNo'])) {

	echo '<br /><br />';
	prnMsg( _('This page must be called with a purchase order number to review'), 'error');

	echo '<table class="table_index">
		<tr><td class="menu_group_item">
                <li><a href="'. $RootPath . '/PO_SelectPurchOrder.php">' . _('Return') . '</a></li>
		</td></tr></table>';
	include('includes/footer.php');
	exit;
}

$ErrMsg = _('The order requested could not be retrieved') . ' - ' . _('the SQL returned the following error');
$OrderHeaderSQL = "SELECT purchorders.*,
						suppliers.supplierid,
						suppliers.suppname,
						suppliers.currcode,
						www_users.realname,
						locations.locationname,
						currencies.decimalplaces AS currdecimalplaces
					FROM purchorders
					INNER JOIN locationusers ON locationusers.loccode=purchorders.intostocklocation AND locationusers.userid='" .  $_SESSION['UserID'] . "' AND locationusers.canview=1
					INNER JOIN locations
					ON locations.loccode=purchorders.intostocklocation
					INNER JOIN suppliers
					ON purchorders.supplierno = suppliers.supplierid
					INNER JOIN currencies
					ON suppliers.currcode = currencies.currabrev
					LEFT JOIN www_users
					ON purchorders.initiator=www_users.userid
					WHERE purchorders.orderno = '" . $_GET['OrderNo'] ."'";

$GetOrdHdrResult = DB_query($OrderHeaderSQL, $ErrMsg);

if (DB_num_rows($GetOrdHdrResult)!=1) {
	echo '<br /><br />';
	if (DB_num_rows($GetOrdHdrResult) == 0){
		prnMsg ( _('Unable to locate this PO Number') . ' '. $_GET['OrderNo'] . '. ' . _('Please look up another one') . '. ' . _('The order requested could not be retrieved') . ' - ' . _('the SQL returned either 0 or several purchase orders'), 'error');
	} else {
		prnMsg ( _('The order requested could not be retrieved') . ' - ' . _('the SQL returned either several purchase orders'), 'error');
	}
        echo '<table class="table_index">
                <tr>
					<td class="menu_group_item">
						<li><a href="'. $RootPath . '/PO_SelectPurchOrder.php">' . _('Return') . '</a></li>
					</td>
				</tr>
				</table>';

	include('includes/footer.php');
	exit;
}
 // the checks all good get the order now

$myrow = DB_fetch_array($GetOrdHdrResult);
$stockLoc=$myrow['intostocklocation'];
$suppid=$myrow['supplierid'];
$deladd=implode_list($myrow['deladd1'] , 
$myrow['deladd2'] , 
$myrow['deladd3'] , 
$myrow['deladd4'] , 
$myrow['deladd5'], 
$myrow['deladd6']);
/* SHOW ALL THE ORDER INFO IN ONE PLACE */
echo '<p class="page_title_text"><img src="'.$RootPath.'/css/'.$Theme.'/images/supplier.png" title="' .
		_('Purchase Order') . '" alt="" />' . ' ' . $Title . '</p>';
echo '<a href="' . $RootPath . '/PO_SelectPurchOrder.php">' . _('Return') . '</a>';
echo '<table class="selection" cellpadding="2">
		<tr>
			<th colspan="8"><b>' .  _('Order Header Details'). '</b></th>
		</tr>
		<tr>
			<td>' . _('Supplier Code'). '</td>
			<td><a href="SelectSupplier.php?SupplierID='.$myrow['supplierid'].'">' . $myrow['supplierid'] . '</a></td>
			<td>' . _('Supplier Name'). '</td>
			<td><a href="SelectSupplier.php?SupplierID='.$myrow['supplierid'].'">' . $myrow['suppname'] . '</a></td>
		</tr>
		<tr>
			<td>' . _('Ordered On'). '</td>
			<td>' . ConvertSQLDate($myrow['orddate']) . '</td>
			<td colsman="2">' . _('Delivery Address'). ':</td>
		</tr>
		<tr>
			<td>' . _('Order Currency'). '</td>
			<td>' . $myrow['currcode'] . '</td>
			<td colspan="2" rowspan="6">' . $deladd . ', '
			
			.'</td>
		</tr>
		<tr>
			<td>' . _('Exchange Rate'). '</td>
			<td>' . $myrow['rate'] . '</td>
		</tr>
		<tr>
			<td>' . _('Deliver Into Location'). '</td>
			<td>' . $myrow['locationname'] . '</td>
		</tr>
		<tr>
			<td>' . _('Initiated By'). '</td>
			<td>' . $myrow['realname'] . '</td>
		</tr>
		<tr>
			<td>' . _('Requisition Ref'). '.</td>
			<td>' . $myrow['requisitionno'] . '</td>
		</tr>
		<tr>
			<td>' .  _('Printing') . '</td>
			<td colspan="3">';

if ($myrow['dateprinted'] == ''){
	echo '<i>' .  _('Not yet printed') . '</i> &nbsp; &nbsp; ';
	echo '[<a href="PO_PDFPurchOrder.php?OrderNo='. $_GET['OrderNo'] .'">' .  _('Print')  . '</a>]';
} else {
	echo _('Printed on').' '. ConvertSQLDate($myrow['dateprinted']). '&nbsp; &nbsp;';
}

echo  '</td>
	</tr>
	<tr>
		<td>' .  _('Status') . '</td>
		<td>' .  _($myrow['status']) . '</td>
	</tr>
	<tr>
		<td>' . _('Comments'). '</td>
		<td colspan="3">' . $myrow['comments'] . '</td>
	</tr>
	<tr>
		<td>' . _('Status Coments') . '</td>
		<td colspan="5">' . html_entity_decode($myrow['stat_comment']) . '</td>
	</tr>
	</table>';

$CurrDecimalPlaces = $myrow['currdecimalplaces'];

echo '<br />';
/*Now get the line items */
$ErrMsg = _('The line items of the purchase order could not be retrieved');
$LineItemsSQL = "SELECT purchorderdetails.*,
						stockmaster.decimalplaces,
						stockmaster.stockid
				FROM purchorderdetails
				LEFT JOIN stockmaster
				ON purchorderdetails.itemcode=stockmaster.stockid
				WHERE purchorderdetails.orderno = '" . $_GET['OrderNo'] ."'
				ORDER BY itemcode";	/*- ADDED: Sort by our item code -*/

$LineItemsResult = DB_query($LineItemsSQL, $ErrMsg);


echo '<table class="selection" cellpadding="0">
		<tr>
			<th colspan="8"><b>' .  _('Order Line Details'). '</b></th>
		</tr>
		<tr>
			<td>' . _('Item Code'). '</td>
			<td>' . _('Item Description'). '</td>
			<td>' . _('Ord Qty'). '</td>
			<td>' . _('Qty Recd'). '</td>
			<td>' . _('Qty Inv'). '</td>
			<td>' . _('Ord Price'). '</td>
			<td>' . _('Chg Price'). '</td>
			<td>' . _('Reqd Date'). '</td>
		</tr>';

$OrderTotal=0;
$RecdTotal=0;
$ivaTotal=0;

while ($myrow=DB_fetch_array($LineItemsResult)) {
	
	$precioFinal=$myrow['unitprice']*(1+$myrow['taxrate']);
	$OrderTotal += ($myrow['quantityord'] * $precioFinal);
	$RecdTotal += ($myrow['quantityrecd'] * $precioFinal);
	$ivaTotal+=$myrow['unitprice']*($myrow['taxrate']);
	$DisplayReqdDate = ConvertSQLDate($myrow['deliverydate']);
	if ($myrow['decimalplaces']!=NULL){
		$DecimalPlaces = $myrow['decimalplaces'];
	} else {
		$DecimalPlaces = 2;
	}
	// if overdue and outstanding quantities, then highlight as so
	if (($myrow['quantityord'] - $myrow['quantityrecd'] > 0)
	  	AND Date1GreaterThanDate2(Date($_SESSION['DefaultDateFormat']), $DisplayReqdDate)){
    	 	echo '<tr class="OsRow">';
	} else {
		echo '<tr class="striped_row">';
	}

	printf ('<td>%s</td>
			<td>%s</td>
			<td class="number">%s</td>
			<td class="number">%s</td>
			<td class="number">%s</td>
			<td class="number">%s</td>
			<td class="number">%s</td>
			<td>%s</td>
			</tr>' ,
			$myrow['itemcode'],
			$myrow['itemdescription'],
			locale_number_format($myrow['quantityord'],$DecimalPlaces),
			locale_number_format($myrow['quantityrecd'],$DecimalPlaces),
			locale_number_format($myrow['qtyinvoiced'],$DecimalPlaces),
			locale_number_format($precioFinal,$CurrDecimalPlaces),
			locale_number_format($myrow['actprice'],$CurrDecimalPlaces),
			$DisplayReqdDate);

}

echo '<tr><td><br /></td>
	</tr>
	<tr>
		<td colspan="4" class="number">' . _('Included tax')  . '</td>
		<td colspan="2" class="number">' . locale_number_format($ivaTotal,$CurrDecimalPlaces) . '</td>
	</tr>
	<tr>
		<td colspan="4" class="number">' . _('Total Order Value Incl. Tax')  . '</td>
		<td colspan="2" class="number">' . locale_number_format($OrderTotal,$CurrDecimalPlaces) . '</td>
	</tr>
	<tr>
		<td colspan="4" class="number">' . _('Total Order Value Received Incl. Tax') . '</td>
		<td colspan="2" class="number">' . locale_number_format($RecdTotal,$CurrDecimalPlaces) . '</td>
	</tr>
	</table>
	<br />';

include ('includes/footer.php');
?>
