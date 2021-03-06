<?php

include('includes/session.php');
$Title = _('Import Price List from .csv file');
include('includes/header.php');
echo '<p class="page_title_text"><img alt="" src="' . $RootPath . '/css/' . $Theme .
		'/images/maintenance.png" title="' .
		$Title . '" />' . ' ' .
		$Title . '</p>';

$FieldHeadings = array(
	'StockID',			//  0 'STOCKID',
	'SalesType',		//  1 'Price list id',
	'Price'				//  3 'Price'
);

if (isset($_FILES['PriceListFile']) and $_FILES['PriceListFile']['name']) { //start file processing
	//check file info
	$FileName = $_FILES['PriceListFile']['name'];
	$TempName  = $_FILES['PriceListFile']['tmp_name'];
	$FileSize = $_FILES['PriceListFile']['size'];
	$FieldTarget = 4;
	$InputError = 0;

	//get file handle
	$FileHandle = fopen($TempName, 'r');

	//get the header row
	$HeadRow = fgetcsv($FileHandle, 10000, ',');

	//check for correct number of fields
	if ( count($HeadRow) != count($FieldHeadings) ) {
		prnMsg (sprintf(_('File contains %u columns, expected %u. Try downloading a new template.'),count($HeadRow), count($FieldHeadings)),'error');
		fclose($FileHandle);
		include('includes/footer.php');
		exit;
	}

	//test header row field name and sequence
	$HeadingColumnNumber = 0;
	foreach ($HeadRow as $HeadField) {
		if ( trim(mb_strtoupper($HeadField)) != trim(mb_strtoupper($FieldHeadings[$HeadingColumnNumber]))) {
			prnMsg (sprintf(_('File contains incorrect headers %u  != . Try downloading a new template.'),mb_strtoupper($headField),mb_strtoupper($FieldHeadings[$HeadingColumnNumber])),'error');  
			fclose($FileHandle);
			include('includes/footer.php');
			exit;
		}
		$HeadingColumnNumber++;
	}

	//start database transaction
	DB_Txn_Begin();

	//loop through file rows
	$LineNumber = 1;
	while ( ($myrow = fgetcsv($FileHandle, 10000, ',')) !== FALSE ) {

		//check for correct number of fields
		$FieldCount = count($myrow);
		if ($FieldCount != $FieldTarget){
			prnMsg (sprintf(_('%u fields required, %u fields received'),$FieldTarget,$fieldCount),'error');
			fclose($FileHandle);
			include('includes/footer.php');
			exit;
		}

		// cleanup the data (csv files often import with empty strings and such)
		$StockID = mb_strtoupper($myrow[0]);
		foreach ($myrow as &$value) {
			$value = trim($value);
			$value = str_replace('"', '', $value);
		}

		//first off check that the item actually exist
		$sql = "SELECT COUNT(stockid) FROM stockmaster WHERE stockid='" . $StockID . "'";
		$result = DB_query($sql);
		$testrow = DB_fetch_row($result);
		if ($testrow[0] == 0) {
			$InputError = 1;
			prnMsg (_('Stock item') . ' "'. $myrow[0]. '" ' . _('does not exist'),'error');
		}
		//Then check that the price list actually exists
		$sql = "SELECT COUNT(typeabbrev) FROM salestypes WHERE typeabbrev='" . $myrow[1] . "'";
		$result = DB_query($sql);
		$testrow = DB_fetch_row($result);
		if ($testrow[0] == 0) {
			$InputError = 1;
			prnMsg (_('Sales type') . ' "' . $myrow[1]. '" ' . _('does not exist'),'error');
		}

		//Finally force the price to be a double
		$myrow[3] = (double)$myrow[3];
		if ($InputError !=1){

			//Firstly close any open prices for this item
			$sql = "UPDATE prices
						SET enddate='" . FormatDateForSQL($_POST['StartDate']) . "'
						WHERE stockid='" . $StockID . "'
						AND enddate>'" . date('Y-m-d') . "'
						AND typeabbrev='" . $myrow[1] . "'";
			$result = DB_query($sql);

			//Insert the price
			$sql = "INSERT INTO prices (stockid,
										typeabbrev,
										currabrev,
										price,
										startdate
									) VALUES (
										'" . $myrow[0] . "',
										'" . $myrow[1] . "',
										'MXN',
										'" . $myrow[2] . "',
										'" . FormatDateForSQL($_POST['StartDate']) . "')";

			$ErrMsg =  _('The price could not be added because');
			$DbgMsg = _('The SQL that was used to add the price failed was');
			$result = DB_query($sql, $ErrMsg, $DbgMsg);
		}

		if ($InputError == 1) { //this row failed so exit loop
			break;
		}
		$LineNumber++;
	}

	if ($InputError == 1) { //exited loop with errors so rollback
		prnMsg(_('Failed on row '. $LineNumber. '. Batch import has been rolled back.'),'error');
		DB_Txn_Rollback();
	} else { //all good so commit data transaction
		DB_Txn_Commit();
		prnMsg( _('Batch Import of') .' ' . $FileName  . ' '. _('has been completed. All transactions saved.'),'success');
	}

	fclose($FileHandle);

} else { //show file upload form

	echo '<form action="' . htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8') . '" method="post" class="noPrint" enctype="multipart/form-data">';
	echo '<div class="centre">';
	echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';
	echo '<div class="page_help_text">' .
			_('This function loads a new sales price list from a comma separated variable (csv) file.') . '<br />' .
			_('The file must contain four columns, and the first row should be the following headers:') . '<br />StockID, SalesType, Price<br />' .
			_('followed by rows containing these four fields for each price to be uploaded.') .  '<br />' .
			_('The StockID and SalesType fields must have a corresponding entry in the stock and price list.') . '</div>';

	echo '<br /><input type="hidden" name="MAX_FILE_SIZE" value="1000000" />' .
			_('Prices effective from') . ':&nbsp;<input type="text" name="StartDate" maxlength="10" size="11" class="date" value="' . date($_SESSION['DefaultDateFormat']) . '" />&nbsp;' .
			_('Upload file') . ': <input name="PriceListFile" type="file" />
			<input type="submit" name="submit" value="' . _('Send File') . '" />
		</div>
		</form>';

}

include('includes/footer.php');

?>
