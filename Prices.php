<?php

include('includes/session.php');
include_once('includes/devstar/prices.inc');
$Title = _('Item Prices');
$ViewTopic = 'Prices';
/*$BookMark = '';// Anchor's id in the manual's html document.*/
include('includes/header.php');
echo '<p class="page_title_text"><img alt="" src="' . $RootPath . '/css/' . $Theme .
		'/images/money_add.png" title="' .
		_('Search') . '" />' . ' ' .
		$Title . '</p>';

echo '<div class="col-12"><a href="' . $RootPath . '/SelectProduct.php">' . _('Back to Items') . '</a></div>';

include('includes/SQL_CommonFunctions.inc');

//initialise no input errors assumed initially before we test
$InputError = 0;

if (isset($_GET['Item'])) {
	$Item = trim(mb_strtoupper($_GET['Item']));
} elseif (isset($_POST['Item'])) {
	$Item = trim(mb_strtoupper($_POST['Item']));
}

if (!isset($_POST['SalesType']) OR $_POST['SalesType']=='') {
	$_POST['SalesType'] = $_SESSION['DefaultPriceList'];
}

if (!isset($_POST['CurrAbrev'])) {
	$_POST['CurrAbrev'] = $_SESSION['CompanyRecord']['currencydefault'];
}

$result = DB_query("SELECT stockmaster.description,
							stockmaster.mbflag
					FROM stockmaster
					WHERE stockmaster.stockid='".$Item."'");
$myrow = DB_fetch_row($result);

if (DB_num_rows($result)==0) {
	prnMsg( _('The part code entered does not exist in the database') . '. ' . _('Only valid parts can have prices entered against them'),'error');
	$InputError=1;
}

if (!isset($Item)) {
	echo '<p>';
	prnMsg (_('An item must first be selected before this page is called') . '. ' . _('The product selection page should call this page with a valid product code'),'error');
	include('includes/footer.php');
	exit;
}

$PartDescription = $myrow[0];

if ($myrow[1]=='K') {
	prnMsg(_('The part selected is a kit set item') .', ' . _('these items explode into their components when selected on an order') . ', ' . _('prices must be set up for the components and no price can be set for the whole kit'),'error');
	exit;
}

if (isset($_POST['submit'])) {
	/* actions to take once the user has clicked the submit button
	ie the page has called itself with some user input */

	//first off validate inputs sensible
	// This gives some date in 1999?? $ZeroDate = Date($_SESSION['DefaultDateFormat'],Mktime(0,0,0,0,0,0));

	if (!is_numeric(filter_number_format($_POST['Price'])) OR $_POST['Price']=='') {
		$InputError = 1;
		prnMsg( _('The price entered must be numeric'),'error');
	}
	if (! Is_Date($_POST['StartDate'])){
		$InputError =1;
		prnMsg (_('The date this price is to take effect from must be entered in the format') . ' ' . $_SESSION['DefaultDateFormat'],'error');
	}
	if ($_POST['EndDate']!='') {
		if (FormatDateForSQL($_POST['EndDate'])!='9999-12-31'){
			if (! Is_Date($_POST['EndDate']) AND $_POST['EndDate']!=''){
				$InputError =1;
				prnMsg (_('The date this price is be in effect to must be entered in the format') . ' ' . $_SESSION['DefaultDateFormat'],'error');
			}
			if (Date1GreaterThanDate2($_POST['StartDate'],$_POST['EndDate']) AND $_POST['EndDate']!='' AND FormatDateForSQL($_POST['EndDate'])!='9999-12-31'){
				$InputError =1;
				prnMsg (_('The end date is expected to be after the start date, enter an end date after the start date for this price'),'error');
			}
			if (Date1GreaterThanDate2(Date($_SESSION['DefaultDateFormat']),$_POST['EndDate']) AND $_POST['EndDate']!='' AND FormatDateForSQL($_POST['EndDate'])!='9999-12-31'){
				$InputError =1;
				prnMsg(_('The end date is expected to be after today. There is no point entering a new price where the effective date is before today!'),'error');
			}
		}
	}
	if (Is_Date($_POST['EndDate'])){
		$SQLEndDate = FormatDateForSQL($_POST['EndDate']);
	} else {
		$SQLEndDate = '9999-12-31';
	}

	$sql = "SELECT COUNT(typeabbrev)
				FROM prices
			WHERE prices.stockid='".$Item."'
			AND startdate='" .FormatDateForSQL($_POST['StartDate']) . "'
			AND enddate ='" . $SQLEndDate . "'
			AND prices.typeabbrev='" . $_POST['SalesType'] . "'
			AND prices.currabrev='" . $_POST['CurrAbrev'] . "'";

	$result = DB_query($sql);
	$myrow = DB_fetch_row($result);

	if ($myrow[0]!=0 AND !isset($_POST['OldTypeAbbrev']) AND !isset($_POST['OldCurrAbrev'])) {
		prnMsg( _('This price has already been entered. To change it you should edit it') , 'warn');
		$InputError =1;
	}


	if (isset($_POST['OldTypeAbbrev']) AND isset($_POST['OldCurrAbrev']) AND mb_strlen($Item)>1 AND $InputError !=1) {

		/* Need to see if there is also a price entered that has an end date after the start date of this price and if so we will need to update it so there is no ambiguity as to which price will be used*/

		//editing an existing price
		$sql = "UPDATE prices SET
					typeabbrev='" . $_POST['SalesType'] . "',
					currabrev='" . $_POST['CurrAbrev'] . "',
					price='" . filter_number_format($_POST['Price']) . "',
					startdate='" . FormatDateForSQL($_POST['StartDate']) . "',
					enddate='" . $SQLEndDate . "'
				WHERE prices.stockid='".$Item."'
				AND startdate='" .$_POST['OldStartDate'] . "'
				AND enddate ='" . $_POST['OldEndDate'] . "'
				AND prices.typeabbrev='" . $_POST['OldTypeAbbrev'] . "'
				AND prices.currabrev='" . $_POST['OldCurrAbrev'] . "'
				AND prices.debtorno=''";

		$ErrMsg = _('Could not be update the existing prices');
		$result = DB_query($sql,$ErrMsg);

		ReSequenceEffectiveDates ($Item, $_POST['SalesType'], $_POST['CurrAbrev']) ;

		prnMsg(_('The price has been updated'),'success');

	} elseif ($InputError !=1) {

	/*Selected price is null cos no item selected on first time round so must be adding a	record must be submitting new entries in the new price form */

		$sql = "INSERT INTO prices (stockid,
									typeabbrev,
									currabrev,
									startdate,
									enddate,
									price)
							VALUES ('" . $Item . "',
								'" . $_POST['SalesType'] . "',
								'" . $_POST['CurrAbrev'] . "',
								'" . FormatDateForSQL($_POST['StartDate']) . "',
								'" . $SQLEndDate. "',
								'" . filter_number_format($_POST['Price']) . "')";
		$ErrMsg = _('The new price could not be added');
		$result = DB_query($sql,$ErrMsg);

		ReSequenceEffectiveDates ($Item, $_POST['SalesType'], $_POST['CurrAbrev']) ;
		prnMsg(_('The new price has been inserted'),'success');
	}

	unset($_POST['Price']);
	unset($_POST['StartDate']);
	unset($_POST['EndDate']);

} elseif (isset($_GET['delete'])) {
//the link to delete a selected record was clicked instead of the submit button

	$sql="DELETE FROM prices
			WHERE prices.stockid = '". $Item ."'
			AND prices.typeabbrev='". $_GET['SalesType'] ."'
			AND prices.currabrev ='". $_GET['CurrAbrev'] ."'
			AND  prices.startdate = '" .$_GET['StartDate'] . "'
			AND  prices.enddate = '" . $_GET['EndDate'] . "'
			AND prices.debtorno=''";
	$ErrMsg = _('Could not delete this price');
	$result = DB_query($sql,$ErrMsg);
	prnMsg( _('The selected price has been deleted'),'success');

}

//Always do this stuff

$sql = "SELECT
		currencies.currency,
        salestypes.sales_type,
		prices.price,
		prices.stockid,
		prices.typeabbrev,
		prices.currabrev,
		prices.startdate,
		prices.enddate,
		currencies.decimalplaces AS currdecimalplaces
	FROM prices
	INNER JOIN salestypes
		ON prices.typeabbrev = salestypes.typeabbrev
	INNER JOIN currencies
		ON prices.currabrev=currencies.currabrev
	WHERE prices.stockid='".$Item."'
	AND prices.debtorno=''
	ORDER BY prices.currabrev,
		prices.typeabbrev,
		prices.startdate";

$result = DB_query($sql);
require_once('includes/CurrenciesArray.php');
if (DB_num_rows($result) > 0) {
	echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" class="col-12">
		<div>
		<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />
		<table class="selection">
		<thead>
			<tr>
				<th colspan="7">' .
				_('Item Code') . ':
				<input type="text" required="required" autofocus="autofocus" name="Item" size="22" value="' . $Item . '" maxlength="20" />
				<input type="submit" name="NewPart" value="' . _('Review Prices') . '" /></th>
			</tr>
			<tr><th class="ascending">' . _('Currency') . '</th>
				<th class="ascending">' . _('Sales Type') . '</th>
				<th class="ascending">' . _('Price') . '</th>
				<th class="ascending">' . _('Start Date') . ' </th>
				<th class="ascending">' . _('End Date') . '</th>';
	if (in_array(5, $_SESSION['AllowedPageSecurityTokens'])) { // If is allow to modify prices.
		echo   '<th colspan="2">' . _('Maintenance') . '</th>';
	}
	echo '</tr>
		</thead>
		<tbody>';

	while ($myrow = DB_fetch_array($result)) {
		if ($myrow['enddate']=='9999-12-31'){
			$EndDateDisplay = _('No End Date');
		} else {
			$EndDateDisplay = ConvertSQLDate($myrow['enddate']);
		}

		echo '<tr class="striped_row">
				<td>' . $CurrencyName[$myrow['currabrev']] . '</td>
				<td>' .  $myrow['sales_type'] . '</td>
				<td class="number">' . locale_number_format($myrow['price'], $myrow['currdecimalplaces']+2) . '</td>
				<td>' . ConvertSQLDate($myrow['startdate']) . '</td>
				<td>' . $EndDateDisplay . '</td>';

		/*Only allow access to modify prices if securiy token 5 is allowed */
		if (in_array(5, $_SESSION['AllowedPageSecurityTokens'])) {
			echo '<td><a href="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?Item=' . $myrow['stockid'] . '&amp;SalesType=' . $myrow['typeabbrev'] . '&amp;CurrAbrev=' . $myrow['currabrev'] . '&amp;Price=' . locale_number_format($myrow['price'],$myrow['currdecimalplaces']) . '&amp;StartDate=' . $myrow['startdate'] . '&amp;EndDate=' . $myrow['enddate'] . '&amp;Edit=1">' . _('Edit') . '</a></td>
				<td><a href="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '?Item=' . $myrow['stockid'] . '&amp;SalesType=' . $myrow['typeabbrev'] . '&amp;CurrAbrev=' . $myrow['currabrev'] . '&amp;StartDate=' . $myrow['startdate'] . '&amp;EndDate=' . $myrow['enddate'] . '&amp;delete=yes" onclick="return confirm(\'' . _('Are you sure you wish to delete this price?') . '\');">' . _('Delete') . '</a></td>';
		}
		echo '</tr>';

	}
	//END WHILE LIST LOOP
	echo '</tbody>
		</table><br />
		</div>
		  </form>';
} else {
	prnMsg(_('There are no prices set up for this part'),'warn');
}

echo '<form method="post" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" class="col-12">';
echo '<div>';
echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
if (isset($_GET['Edit'])){
	echo '<input type="hidden" name="OldTypeAbbrev" value="' . $_GET['SalesType'] .'" />';
	echo '<input type="hidden" name="OldCurrAbrev" value="' . $_GET['CurrAbrev'] . '" />';
	echo '<input type="hidden" name="OldStartDate" value="' . $_GET['StartDate'] . '" />';
	echo '<input type="hidden" name="OldEndDate" value="' . $_GET['EndDate'] . '" />';
	$_POST['CurrAbrev'] = $_GET['CurrAbrev'];
	$_POST['SalesType'] = $_GET['SalesType'];
	/*the price sent with the get is sql format price so no need to filter */
	$_POST['Price'] = $_GET['Price'];
	$_POST['StartDate'] = ConvertSQLDate($_GET['StartDate']);
	if ($_GET['EndDate']=='' OR $_GET['EndDate']=='9999-12-31'){
		$_POST['EndDate'] = '';
	} else {
		$_POST['EndDate'] = ConvertSQLDate($_GET['EndDate']);
	}
}

echo '<br /><table class="selection">';
echo '<tr><th colspan="5"><h3>' . $Item . ' - ' . $PartDescription . '</h3></th></tr>';
echo '<tr><td>' . _('Currency') . ':</td>
			<td><input type="hidden" name="CurrAbrev" value="MXN"/>Peso mexicano</td></tr>';

DB_free_result($result);

echo '<tr>
			<td>' . _('Sales Type Price List') . ':</td>
			<td>';

getSalesTypesSelect($_POST['SalesType']);

echo '</td></tr>';

DB_free_result($result);

if (!isset($_POST['StartDate'])){
	$_POST['StartDate'] = Date($_SESSION['DefaultDateFormat']);
}
if (!isset($_POST['EndDate'])){
	$_POST['EndDate'] = '';
}
echo '<tr><td>' . _('Price Effective From Date')  . ':</td>
			<td><input type="text" class="date" name="StartDate" required="required" size="10" maxlength="10" title="' . _('Enter the date from which this price should take effect.') . '" value="' . $_POST['StartDate'] . '" /></td></tr>';
echo '<tr><td>' . _('Price Effective To Date')  . ':</td>
			<td><input type="text" class="date" name="EndDate" size="10" maxlength="10" title="' . _('Enter the date to which this price should be in effect to, or leave empty if the price should continue indefinitely') . '" value="' . $_POST['EndDate'] . '" />';
echo '<input type="hidden" name="Item" value="' . $Item.'" /></td></tr>';
echo '<tr><td>' . _('Price') . ':</td>
          <td>
          <input type="text" class="number" required="required" name="PriceTx" id="PriceTx" size="12" maxlength="11" value="';
          if (isset($_POST['Price'])) {
	         echo $_POST['Price']*1.16;
          }
          echo '" />
     </td></tr>
	 <tr><td>' . _('Excl Tax') . ':</td>
          <td>
          <input type="text" class="number" readonly="readonly" name="Price" id="PriceNoTax" size="12" maxlength="11" value="';
          if (isset($_POST['Price'])) {
	         echo $_POST['Price'];
          }
          echo '" />
     </td></tr>';
	 ?>

</table>
<br /><div class="centre">
<input type="submit" name="submit" value="<?= _('Enter')?>/<?=_('Amend Price')?>" />
</div>


</div>
      </form>

<?php

addScriptList("/javascripts/prices.js");
include('includes/footer.php');




?>
