<?php
include ('includes/session.php');
$Title = _('Supplier Maintenance');
/* webERP manual links before header.php */
$ViewTopic = 'AccountsPayable';
$BookMark = 'NewSupplier';
include ('includes/header.php');

include ('includes/SQL_CommonFunctions.inc');
include ('includes/CountriesArray.php');

function Is_ValidAccount($ActNo) {

	if (mb_strlen($ActNo) < 16) {
		echo _('NZ account numbers must have 16 numeric characters in it');
		return False;
	}

	if (!Is_double((double)$ActNo)) {
		echo _('NZ account numbers entered must use all numeric characters in it');
		return False;
	}

	$BankPrefix = mb_substr($ActNo, 0, 2);
	$BranchNumber = (int)(mb_substr($ActNo, 3, 4));


	if ($BankPrefix == '29') {
		echo _('NZ Accounts codes with the United Bank are not verified') . ', ' . _('be careful to enter the correct account number');
		exit;
	}

	//Verify correct branch details
	switch ($BankPrefix) {

		case '01':
			if (!(($BranchNumber >= 1 and $BranchNumber <= 999) or ($BranchNumber >= 1100 and $BranchNumber <= 1199))) {
				echo _('ANZ branches must be between 0001 and 0999 or between 1100 and 1199') . '. ' . _('The branch number used is invalid');
				return False;
			}
		break;
		case '02':
			if (!(($BranchNumber >= 1 and $BranchNumber <= 999) or ($BranchNumber >= 1200 and $BranchNumber <= 1299))) {
				echo _('Bank Of New Zealand branches must be between 0001 and 0999 or between 1200 and 1299') . '. ' . _('The branch number used is invalid');
				return False;
				exit;
			}
		break;
		case '03':
			if (!(($BranchNumber >= 1 and $BranchNumber <= 999) or ($BranchNumber >= 1300 and $BranchNumber <= 1399))) {
				echo _('Westpac Trust branches must be between 0001 and 0999 or between 1300 and 1399') . '. ' . _('The branch number used is invalid');
				return False;
				exit;
			}
		break;

		case '06':
			if (!(($BranchNumber >= 1 and $BranchNumber <= 999) or ($BranchNumber >= 1400 and $BranchNumber <= 1499))) {
				echo _('National Bank branches must be between 0001 and 0999 or between 1400 and 1499') . '. ' . _('The branch number used is invalid');
				return False;
				exit;
			}
		break;

		case '08':
			if (!($BranchNumber >= 6500 and $BranchNumber <= 6599)) {
				echo _('National Australia branches must be between 6500 and 6599') . '. ' . _('The branch number used is invalid');
				return False;
				exit;
			}
		break;
		case '09':
			if ($BranchNumber != 0) {
				echo _('The Reserve Bank branch should be 0000') . '. ' . _('The branch number used is invalid');
				return False;
				exit;
			}
		break;
		case '12':

			//"13" "14" "15", "16", "17", "18", "19", "20", "21", "22", "23", "24":
			if (!($BranchNumber >= 3000 and $BranchNumber <= 4999)) {
				echo _('Trust Bank and Regional Bank branches must be between 3000 and 4999') . '. ' . _('The branch number used is invalid');
				return False;
				exit;
			}
		break;

		case '11':
			if (!($BranchNumber >= 5000 and $BranchNumber <= 6499)) {
				echo _('Post Office Bank branches must be between 5000 and 6499') . '. ' . _('The branch number used is invalid');
				return False;
				exit;
			}
		break;

		case '25':
			if (!($BranchNumber >= 2500 and $BranchNumber <= 2599)) {
				echo _('Countrywide Bank branches must be between 2500 and 2599') . '. ' . _('The branch number used is invalid');
				return False;
				exit;
			}
		break;
		case '29':
			if (!($BranchNumber >= 2150 and $BranchNumber <= 2299)) {
				echo _('United Bank branches must be between 2150 and 2299') . '. ' . _('The branch number used is invalid');
				return False;
				exit;
			}
		break;

		case '30':
			if (!($BranchNumber >= 2900 and $BranchNumber <= 2949)) {
				echo _('Hong Kong and Shanghai branches must be between 2900 and 2949') . '. ' . _('The branch number used is invalid');
				return False;
				exit;
			}
		break;

		case '31':
			if (!($BranchNumber >= 2800 and $BranchNumber <= 2849)) {
				echo _('Citibank NA branches must be between 2800 and 2849') . '. ' . _('The branch number used is invalid');
				return False;
				exit;
			}
		break;

		case '33':
			if (!($BranchNumber >= 6700 and $BranchNumber <= 6799)) {
				echo _('Rural Bank branches must be between 6700 and 6799') . '. ' . _('The branch number used is invalid');
				return False;
				exit;
			}
		break;

		default:
			echo _('The prefix') . ' - ' . $BankPrefix . ' ' . _('is not a valid New Zealand Bank') . '.<br />' . _('If you are using webERP outside New Zealand error trapping relevant to your country should be used');
			return False;
			exit;

	} // end of first Bank prefix switch
	for ($i = 3;$i <= 14;$i++) {

		$DigitVal = (double)(mb_substr($ActNo, $i, 1));

		switch ($i) {
			case 3:
				if ($BankPrefix == '08' or $BankPrefix == '09' or $BankPrefix == '25' or $BankPrefix == '33') {
					$CheckSum = 0;
				} else {
					$CheckSum = $CheckSum + ($DigitVal * 6);
				}
			break;

			case 4:
				if ($BankPrefix == '08' or $BankPrefix == '09' or $BankPrefix == '25' or $BankPrefix == '33') {
					$CheckSum = 0;
				} else {
					$CheckSum = $CheckSum + ($DigitVal * 3);
				}
			break;

			case 5:
				if ($BankPrefix == '08' or $BankPrefix == '09' or $BankPrefix == '25' or $BankPrefix == '33') {
					$CheckSum = 0;
				} else {
					$CheckSum = $CheckSum + ($DigitVal * 7);
				}
			break;

			case 6:
				if ($BankPrefix == '08' or $BankPrefix == '09' or $BankPrefix == '25' or $BankPrefix == '33') {
					$CheckSum = 0;
				} else {
					$CheckSum = $CheckSum + ($DigitVal * 9);
				}
			break;

			case 7:
				if ($BankPrefix == '08') {
					$CheckSum = $CheckSum + $DigitVal * 7;
				} elseif ($BankPrefix == '25' or $BankPrefix == '33') {
					$CheckSum = $CheckSum + $DigitVal * 1;
				}
			break;

			case 8:
				if ($BankPrefix == '08') {
					$CheckSum = $CheckSum + ($DigitVal * 6);
				} elseif ($BankPrefix == '09') {
					$CheckSum = 0;
				} elseif ($BankPrefix == '25' or $BankPrefix == '33') {
					$CheckSum = $CheckSum + $DigitVal * 7;
				} else {
					$CheckSum = $CheckSum + $DigitVal * 10;
				}
			break;

			case 9:
				if ($BankPrefix == '09') {
					$CheckSum = 0;
				} elseif ($BankPrefix == '25' or $BankPrefix == '33') {
					$CheckSum = $CheckSum + $DigitVal * 3;
				} else {
					$CheckSum = $CheckSum + $DigitVal * 5;
				}
			break;

			case 10:
				if ($BankPrefix == '08') {
					$CheckSum = $CheckSum + $DigitVal * 4;
				} elseif ($BankPrefix == '09') {
					if (($DigitVal * 5) > 9) {
						$CheckSum = $CheckSum + (int)mb_substr((string)($DigitVal * 5), 0, 1) + (int)mb_substr((string)($DigitVal * 5), mb_strlen((string)($DigitVal * 5)) - 1, 1);
					} else {
						$CheckSum = $CheckSum + $DigitVal * 5;
					}
				} elseif ($BankPrefix == '25' or $BankPrefix == '33') {
					$CheckSum = $CheckSum + $DigitVal;
				} else {
					$CheckSum = $CheckSum + $DigitVal * 8;
				}
			break;

			case 11:
				if ($BankPrefix == '08') {
					$CheckSum = $CheckSum + $DigitVal * 3;
				} elseif ($BankPrefix == '09') {
					if (($DigitVal * 4) > 9) {
						$CheckSum = $CheckSum + (int)mb_substr(($DigitVal * 4), 0, 1) + (int)mb_substr(($DigitVal * 4), mb_strlen($DigitVal * 4) - 1, 1);
					} else {
						$CheckSum = $CheckSum + $DigitVal * 4;
					}
				} elseif ($BankPrefix == '25' or $BankPrefix == '33') {
					$CheckSum = $CheckSum + $DigitVal * 7;
				} else {
					$CheckSum = $CheckSum + $DigitVal * 4;
				}
			break;

			case 12:
				if ($BankPrefix == '25' or $BankPrefix == '33') {
					$CheckSum = $CheckSum + $DigitVal * 3;
				} elseif ($BankPrefix == '09') {
					if (($DigitVal * 3) > 9) {
						$CheckSum = $CheckSum + (int)mb_substr(($DigitVal * 3), 0, 1) + (int)mb_substr(($DigitVal * 3), mb_strlen($DigitVal * 3) - 1, 1);
					} else {
						$CheckSum = $CheckSum + $DigitVal * 3;
					}
				} else {
					$CheckSum = $CheckSum + $DigitVal * 2;
				}
			break;

			case 13:
				if ($BankPrefix == '09') {
					if (($DigitVal * 2) > 9) {
						$CheckSum = $CheckSum + (int)mb_substr(($DigitVal * 2), 0, 1) + (int)mb_substr(($DigitVal * 2), mb_strlen($DigitVal * 2) - 1, 1);
					} else {
						$CheckSum = $CheckSum + $DigitVal * 2;
					}
				} else {
					$CheckSum = $CheckSum + $DigitVal;
				}
			break;

			case 14:
				if ($BankPrefix == '09') {
					$CheckSum = $CheckSum + $DigitVal;
				}
			break;
		} //end switch
		
	} //end for loop
	if ($BankPrefix == '25' or $BankPrefix == '33') {
		if ($CheckSum / 10 - (int)($CheckSum / 10) != 0) {
			echo '<p>' . _('The account number entered does not meet the banking check sum requirement and cannot be a valid account number');
			return False;
		}
	} else {
		if ($CheckSum / 11 - (int)($CheckSum / 11) != 0) {
			echo '<p>' . _('The account number entered does not meet the banking check sum requirement and cannot be a valid account number');
			return False;
		}
	}

} //End Function

define('ATT_SELECTED', 'selected="selected" ');
$DateString = Date($_SESSION['DefaultDateFormat']);
$submitText=_('Insert New Supplier');


if (isset($_GET['SupplierID'])) {
	$SupplierID = mb_strtoupper($_GET['SupplierID']);
} elseif (isset($_POST['SupplierID'])) {
	$SupplierID = mb_strtoupper($_POST['SupplierID']);
} else {
	unset($SupplierID);
}
$isNew=!isset($SupplierID) ||
		 ( isset($_POST['New']) && $_POST['New'] );

$InputError = 0;

$dodelete= (isset($_GET['action']) and $_GET['action']=='dodelete') ;
if (isset($Errors)) {
	unset($Errors);
}
$Errors = Array();
if (isset($_POST['submit'])) {

	//initialise no input errors assumed initially before we test
	$i = 1;
	/* actions to take once the user has clicked the submit button
	 ie the page has called itself with some user input */

	//first off validate inputs sensible
	if(isset($_POST['New'])&&$_POST['New']){
		if (mb_strlen($SupplierID) == 0 && $_SESSION['AutoSupplierNo'] == 1) {
			/* system assigned, sequential, numeric */
			$SupplierID = GetNextTransNo(600);
		}
		$SQL = "SELECT COUNT(supplierid) FROM suppliers WHERE supplierid='" . $SupplierID . "'";
		$Result = DB_query($SQL);
		$MyRow = DB_fetch_row($Result);
		if ($MyRow[0] > 0 ) {
			$InputError = 1;
			prnMsg(_('The supplier number already exists in the database'), 'error');
			$Errors[$i] = 'ID';
			$i++;
		}
	}
	if (mb_strlen(trim($_POST['SuppName'])) > 40 or mb_strlen(trim($_POST['SuppName'])) == 0 or trim($_POST['SuppName']) == '') {

		$InputError = 1;
		prnMsg(_('The supplier name must be entered and be forty characters or less long'), 'error');
		$Errors[$i] = 'Name';
		$i++;
	}
	if ($_SESSION['AutoSupplierNo'] == 0 and mb_strlen($SupplierID) == 0) {
		$InputError = 1;
		prnMsg(_('The Supplier Code cannot be empty'), 'error');
		$Errors[$i] = 'ID';
		$i++;
	}
	if (ContainsIllegalCharacters($SupplierID)) {
		$InputError = 1;
		prnMsg(_('The supplier code cannot contain any of the illegal characters'), 'error');
		$Errors[$i] = 'ID';
		$i++;
	}
	if (mb_strlen($_POST['Phone']) > 25) {
		$InputError = 1;
		prnMsg(_('The telephone number must be 25 characters or less long'), 'error');
		$Errors[$i] = 'Telephone';
		$i++;
	}
	if (mb_strlen($_POST['Phone2']) > 25) {
		$InputError = 1;
		prnMsg(_('The telephone number must be 25 characters or less long'), 'error');
		$Errors[$i] = 'Phone2';
		$i++;
	}
	if (mb_strlen($_POST['Email']) > 55) {
		$InputError = 1;
		prnMsg(_('The email address must be 55 characters or less long'), 'error');
		$Errors[$i] = 'Email';
		$i++;
	}
	if (mb_strlen($_POST['Email']) > 0 and !IsEmailAddress($_POST['Email'])) {
		$InputError = 1;
		prnMsg(_('The email address is not correctly formed'), 'error');
		$Errors[$i] = 'Email';
		$i++;
	}
	if (mb_strlen($_POST['URL']) > 50) {
		$InputError = 1;
		prnMsg(_('The URL address must be 50 characters or less long'), 'error');
		$Errors[$i] = 'URL';
		$i++;
	}
	if (mb_strlen($_POST['BankRef']) > 12) {
		$InputError = 1;
		prnMsg(_('The bank reference text must be less than 12 characters long'), 'error');
		$Errors[$i] = 'BankRef';
		$i++;
	}
	if (!Is_Date($_POST['SupplierSince'])) {
		$InputError = 1;
		prnMsg(_('The supplier since field must be a date in the format') . ' ' . $_SESSION['DefaultDateFormat'], 'error');
		$Errors[$i] = 'SupplierSince';
		$i++;
	}

	if ($InputError != 1) {
		$addressSt=str_split($_POST['AddressStreet'],40);
		$SQL_SupplierSince = FormatDateForSQL($_POST['SupplierSince']);
		$latitude = 0;
		$longitude = 0;
		if ($_SESSION['geocode_integration'] == 1) {
			// Get the lat/long from our geocoding host
			$SQL = "SELECT * FROM geocode_param WHERE 1";
			$ErrMsg = _('An error occurred in retrieving the information');
			$Resultgeo = DB_query($SQL, $ErrMsg);
			$row = DB_fetch_array($Resultgeo);
			$api_key = $row['geocode_key'];
			$map_host = $row['map_host'];
			define('MAPS_HOST', $map_host);
			define('KEY', $api_key);
			// check that some sane values are setup already in geocode tables, if not skip the geocoding but add the record anyway.
			if ($map_host == "") {
				echo '<div class="warn">' . _('Warning - Geocode Integration is enabled, but no hosts are setup.  Go to Geocode Setup') . '</div>';
			} else {
				$address = urlencode($_POST['AdressStreet'] . ', ' .$_POST['ciudad'] . ', ' .$_POST['estado'] . ', ' . $_POST['cpostal'] . ', ' . $_POST['country']);
				$base_url = "http://" . MAPS_HOST . "/maps/api/geocode/xml?address=";
				$request_url = $base_url . $address . ',&sensor=true';

				$xml = simplexml_load_string(utf8_encode(file_get_contents($request_url))) or die("url not loading");
				//			$xml = simplexml_load_file($request_url) or die("url not loading");
				$coordinates = $xml->Response->Placemark->Point->coordinates;

				$status = $xml->status;
				if (strcmp($status, 'OK') == 0) {
					// Successful geocode
					$geocode_pending = false;
					// Format: Longitude, Latitude, Altitude
					$latitude = $xml->result->geometry->location->lat;
					$longitude = $xml->result->geometry->location->lng;
				} else {
					// failure to geocode
					$geocode_pending = false;
					echo '<p>' . _('Address') . ': ' . $address . ' ' . _('failed to geocode') . "\n";
					echo _('Received status') . ' ' . $status . "\n" . '</p>';
				}
			}
		}
		if (!isset($_POST['New'])||!$_POST['New']) {

			$supptranssql = "SELECT supplierno
							FROM supptrans
							WHERE supplierno='" . $SupplierID . "'";
			$suppresult = DB_query($supptranssql);
			$supptrans = DB_num_rows($suppresult);

			$suppcurrssql = "SELECT currcode
							FROM suppliers
							WHERE supplierid='" . $SupplierID . "'";
			$currresult = DB_query($suppcurrssql);
			$suppcurr = DB_fetch_row($currresult);

			if ($supptrans == 0) {
				$SQL = "UPDATE suppliers SET suppname='" . $_POST['SuppName'] . "',
							address1='" . $addressSt[0] . "',
							address2='" . ($addressSt[1]??"") . "',
							address3='" .$_POST['ciudad'] . "',
							address4='" .$_POST['estado'] . "',
							address5='" .$_POST['cpostal'] . "',
							address6='" . $_POST['country'] . "',
							telephone='" . $_POST['Phone'] . "',
							fax = '" . $_POST['Phone2'] . "',
							email = '" . $_POST['Email'] . "',
							url = '" . $_POST['URL'] . "',
							supptype = '" . $_POST['SupplierType'] . "',
							currcode='" . $_POST['CurrCode'] . "',
							suppliersince='" . $SQL_SupplierSince . "',
							paymentterms='" . $_POST['PaymentTerms'] . "',
							bankpartics='" . $_POST['BankPartics'] . "',
							bankref='" . $_POST['BankRef'] . "',
					 		bankact='" . $_POST['BankAct'] . "',
							remittance='" . $_POST['Remittance'] . "',
							taxgroupid='" . $_POST['TaxGroup'] . "',
							factorcompanyid='" . $_POST['FactorID'] . "',
							lat='" . $latitude . "',
							lng='" . $longitude . "',
							taxref='" . $_POST['TaxRef'] . "',
							defaultshipper='" . $_POST['DefaultShipper'] . "',
							defaultgl='" . $_POST['DefaultGL'] . "'
						WHERE supplierid = '" . $SupplierID . "'";
			} else {
				if ($suppcurr[0] != $_POST['CurrCode']) {
					prnMsg(_('Cannot change currency code as transactions already exist'), 'info');
				}
				$SQL = "UPDATE suppliers SET suppname='" . $_POST['SuppName'] . "',
							address1='" . $addressSt[0] . "',
							address2='" . ($addressSt[1]??"") . "',
							address3='" .$_POST['ciudad'] . "',
							address4='" .$_POST['estado'] . "',
							address5='" . $_POST['cpostal'] . "',
							address6='" . $_POST['country'] . "',
							telephone='" . $_POST['Phone'] . "',
							fax = '" . $_POST['Phone2'] . "',
							email = '" . $_POST['Email'] . "',
							url = '" . $_POST['URL'] . "',
							supptype = '" . $_POST['SupplierType'] . "',
							suppliersince='" . $SQL_SupplierSince . "',
							paymentterms='" . $_POST['PaymentTerms'] . "',
							bankpartics='" . $_POST['BankPartics'] . "',
							bankref='" . $_POST['BankRef'] . "',
					 		bankact='" . $_POST['BankAct'] . "',
							remittance='" . $_POST['Remittance'] . "',
							taxgroupid='" . $_POST['TaxGroup'] . "',
							factorcompanyid='" . $_POST['FactorID'] . "',
							lat='" . $latitude . "',
							lng='" . $longitude . "',
							taxref='" . $_POST['TaxRef'] . "',
							defaultshipper='" . $_POST['DefaultShipper'] . "',
							defaultgl='" . $_POST['DrfaultGL'] . "'
						WHERE supplierid = '" . $SupplierID . "'";
			}

			$ErrMsg = _('The supplier could not be updated because');
			$DbgMsg = _('The SQL that was used to update the supplier but failed was');
			// echo $SQL;
			$Result = DB_query($SQL, $ErrMsg, $DbgMsg);

			prnMsg(_('The supplier master record for') . ' ' . $SupplierID . ' ' . _('has been updated'), 'success');

		} else { //its a new supplier

			$SQL = "INSERT INTO suppliers (supplierid,
										suppname,
										address1,
										address2,
										address3,
										address4,
										address5,
										address6,
										telephone,
										fax,
										email,
										url,
										supptype,
										currcode,
										suppliersince,
										paymentterms,
										bankpartics,
										bankref,
										bankact,
										remittance,
										taxgroupid,
										factorcompanyid,
										lat,
										lng,
										taxref,
										defaultshipper,
										defaultgl)
								 VALUES ('" . $SupplierID . "',
								 	'" . $_POST['SuppName'] . "',
								 	'" . $addressSt[0] . "',
									'" . ($addressSt[1]??"") . "',
									'" .$_POST['ciudad'] . "',
									'" .$_POST['estado'] . "',
									'" .$_POST['cpostal'] . "',
									'" . $_POST['country'] . "',
									'" . $_POST['Phone'] . "',
									'" . $_POST['Phone2'] . "',
									'" . $_POST['Email'] . "',
									'" . $_POST['URL'] . "',
									'" . $_POST['SupplierType'] . "',
									'" . $_POST['CurrCode'] . "',
									'" . $SQL_SupplierSince . "',
									'" . $_POST['PaymentTerms'] . "',
									'" . $_POST['BankPartics'] . "',
									'" . $_POST['BankRef'] . "',
									'" . $_POST['BankAct'] . "',
									'" . $_POST['Remittance'] . "',
									'" . $_POST['TaxGroup'] . "',
									'" . $_POST['FactorID'] . "',
									'" . $latitude . "',
									'" . $longitude . "',
									'" . $_POST['TaxRef'] . "',
									'" . $_POST['DefaultShipper'] . "',
									'" . $_POST['DefaultGL'] . "'
								)";
						
			$ErrMsg = _('The supplier') . ' ' . $_POST['SuppName'] . ' ' . _('could not be added because');
			$DbgMsg = _('The SQL that was used to insert the supplier but failed was');

			$Result = DB_query($SQL, $ErrMsg, $DbgMsg);

			prnMsg(_('A new supplier for') . ' ' . $_POST['SuppName'] . ' ' . _('has been added to the database'), 'success');

			echo '<p>
				<a href="' . $RootPath . '/SupplierContacts.php?SupplierID=' . $SupplierID . '">' . _('Review Supplier Contact Details') . '</a>
				</p>';

			unset($SupplierID);
			unset($_POST['SuppName']);
			unset($_POST['AddressStreet']);
			unset($_POST['ciudad']);
			unset($_POST['estado']);
			unset($_POST['cpostal']);
			unset($_POST['country']);
			unset($_POST['Phone']);
			unset($_POST['Phone2']);
			unset($_POST['Email']);
			unset($_POST['URL']);
			unset($_POST['SupplierType']);
			unset($_POST['CurrCode']);
			unset($SQL_SupplierSince);
			unset($_POST['PaymentTerms']);
			unset($_POST['BankPartics']);
			unset($_POST['BankRef']);
			unset($_POST['BankAct']);
			unset($_POST['Remittance']);
			unset($_POST['TaxGroup']);
			unset($_POST['FactorID']);
			unset($_POST['TaxRef']);
			unset($_POST['DefaultGL']);

		}

	} else {

		prnMsg(_('Validation failed') . _('no updates or deletes took place'), 'warn');

	}

} elseif (isset($_POST['delete']) and $_POST['delete'] != '') {

	//the link to delete a selected record was clicked instead of the submit button
	$CancelDelete = 0;

	// PREVENT DELETES IF DEPENDENT RECORDS IN 'SuppTrans' , PurchOrders, SupplierContacts
	$SQL = "SELECT COUNT(*) FROM supptrans WHERE supplierno='" . $SupplierID . "'";
	$Result = DB_query($SQL);
	$MyRow = DB_fetch_row($Result);
	if ($MyRow[0] > 0) {
		$CancelDelete = 1;
		prnMsg(_('Cannot delete this supplier because there are transactions that refer to this supplier'), 'warn');
		echo '<br />' . _('There are') . ' ' . $MyRow[0] . ' ' . _('transactions against this supplier');

	} else {
		$SQL = "SELECT COUNT(*) FROM purchorders WHERE supplierno='" . $SupplierID . "'";
		$Result = DB_query($SQL);
		$MyRow = DB_fetch_row($Result);
		if ($MyRow[0] > 0) {
			$CancelDelete = 1;
			prnMsg(_('Cannot delete the supplier record because purchase orders have been created against this supplier'), 'warn');
			echo '<br />' . _('There are') . ' ' . $MyRow[0] . ' ' . _('orders against this supplier');
		} else {
			$SQL = "SELECT COUNT(*) FROM suppliercontacts WHERE supplierid='" . $SupplierID . "'";
			$Result = DB_query($SQL);
			$MyRow = DB_fetch_row($Result);
			if ($MyRow[0] > 0) {
				$CancelDelete = 1;
				prnMsg(_('Cannot delete this supplier because there are supplier contacts set up against it') . ' - <a href="' . $RootPath . '/SupplierContacts.php?SupplierID=' . $SupplierID . '">' . _('delete these first') . '</a>' , 'warn');
				echo '<br />' . _('There are') . ' ' . $MyRow[0] . ' ' . _('supplier contacts relating to this supplier');

			}
		}

	}
	if ($CancelDelete == 0) {
		$SQL = "DELETE FROM suppliers WHERE supplierid='" . $SupplierID . "'";
		$Result = DB_query($SQL);
		prnMsg(_('Supplier record for') . ' ' . $SupplierID . ' ' . _('has been deleted'), 'success');
		unset($SupplierID);
		unset($_SESSION['SupplierID']);
	} //end if Delete supplier
	
}

if (isset($SupplierID)) {

	//SupplierID exists - either passed when calling the form or from the form itself


	$submitText=_('Add These New Supplier Details');
	if (!$isNew) {
		$SQL = "SELECT supplierid,
				suppname,
				address1,
				address2,
				address3,
				address4,
				address5,
				address6,
				telephone,
				fax,
				email,
				url,
				supptype,
				currcode,
				suppliersince,
				paymentterms,
				bankpartics,
				bankref,
				bankact,
				remittance,
				taxgroupid,
				factorcompanyid,
				taxref,
				defaultshipper,
				defaultgl
			FROM suppliers
			WHERE supplierid = '" . $SupplierID . "'";

		$Result = DB_query($SQL);
		$MyRow = DB_fetch_array($Result);

		$_POST['SuppName'] = stripcslashes($MyRow['suppname']);
		$_POST['AddressStreet'] = stripcslashes($MyRow['address1'].$MyRow['address2']);
		$_POST['ciudad'] = stripcslashes($MyRow['address3']);
		$_POST['estado'] = stripcslashes($MyRow['address4']);
		$_POST['cpostal'] = stripcslashes($MyRow['address5']);
		$_POST['country'] = stripcslashes($MyRow['address6']);
		$_POST['CurrCode'] = stripcslashes($MyRow['currcode']);
		$_POST['Phone'] = $MyRow['telephone'];
		$_POST['Phone2'] = $MyRow['fax'];
		$_POST['Email'] = $MyRow['email'];
		$_POST['URL'] = $MyRow['url'];
		$_POST['SupplierType'] = $MyRow['supptype'];
		$_POST['SupplierSince'] = ConvertSQLDate($MyRow['suppliersince']);
		$_POST['PaymentTerms'] = $MyRow['paymentterms'];
		$_POST['BankPartics'] = stripcslashes($MyRow['bankpartics']);
		$_POST['Remittance'] = $MyRow['remittance'];
		$_POST['BankRef'] = stripcslashes($MyRow['bankref']);
		$_POST['BankAct'] = $MyRow['bankact'];
		$_POST['TaxGroup'] = $MyRow['taxgroupid'];
		$_POST['FactorID'] = $MyRow['factorcompanyid'];
		$_POST['TaxRef'] = $MyRow['taxref'];
		$_POST['DefaultGL'] = $MyRow['defaultgl'];
		$_POST['DefaultShipper'] = $MyRow['defaultshipper'];

		$submitText=_('Update Supplier');
	} 
} // end of main ifs
$ResultSType = DB_query("SELECT typeid, typename FROM suppliertype");

$ResultPayTer = DB_query("SELECT terms, termsindicator FROM paymentterms");
// Default_Shipper
$SQL = "SELECT shipper_id, shippername FROM shippers orDER BY shippername";
$ErrMsg = _('Could not load shippers');
$ResultShipper = DB_query($SQL, $ErrMsg);

$ResultAccs = DB_query("SELECT accountcode,
					accountname
				FROM chartmaster INNER JOIN accountgroups
				ON chartmaster.group_=accountgroups.groupname
				WHERE accountgroups.pandl=1
				ORDER BY chartmaster.accountcode");
$SQL = "SELECT taxgroupid, taxgroupdescription FROM taxgroups";
$ResultTaxG = DB_query($SQL);
?>
<p class="page_title_text">
	<img src="<?=$RootPath;?>/css/<?=$Theme?>/images/supplier.png" title="_('Search') ?>" alt="" /> <?=_('Suppliers') ?></p>
	<?php
if (isset($SupplierID)) {
	?>
	<p>
		<a href="<?=$RootPath;?>/SupplierContacts.php?SupplierID=<?=$SupplierID?>"><?= _('Review Supplier Contact Details') ?></a>
	</p>
<?php }
$self=htmlspecialchars($_SERVER['PHP_SELF'], ENT_QUOTES, 'UTF-8');

?>

	<form method="post" action="<?=$self?>">
	<div>
	<input type="hidden" name="FormID" value="<?=$_SESSION['FormID']?>" />

	<input type="hidden" name="New" value="<?=$isNew?>" />
	<input type="hidden" name="FactorID" value="0" />
<?php
	if(isset($SupplierID)){
		?>
	<input type="hidden" name="SupplierID" value="<?=$SupplierID?>" />
	<?php }?>
	<table class="selection">

<?php
	$attClass['SuppName']=in_array('SuppName', $Errors) ? 'class="inputerror" ' : '';
	$attClass['Phone']=in_array('Phone', $Errors) ? 'class="inputerror" ' : '';
	$attClass['Phone2']=in_array('Phone2', $Errors) ? 'class="inputerror" ' : '';
	$attClass['Email']=in_array('Email', $Errors) ? 'class="inputerror" ' : '';
	$attClass['URL']=in_array('URL', $Errors) ? 'class="inputerror" ' : '';
	$attClass['SupplierSince']=in_array('SupplierSince', $Errors) ? ' inputerror' : '';
	$SuppSinceVal=$_POST['SupplierSince']??$DateString;
	$attClass['BankRef']=in_array('BankRef', $Errors) ? ' inputerror' : '';
	/* if $AutoSupplierNo is off (not 0) then provide an input box for the SupplierID to manually assigned */
	if ($_SESSION['AutoSupplierNo'] == 0) {
		$attClass['ID']=in_array('ID', $Errors) ? 'class="inputerror" ' : '';


		?>
		<tr><td><?=_('Supplier Code') ?>:</td>
			<td><input type="text" <?=$attClass['ID']?>data-type="no-illegal-chars" 
				title="<?= _('The supplier id should not be within 10 legal characters and cannot be blank') ?>" 
				required="required" name="SupplierID"  value="<?=$SupplierID??''?>"
				placeholder="<?= _('within 10 characters') ?>" size="11" maxlength="10" /></td>
			</tr>
			<?php
	}
	?>
	<tr>
		<td><label for="SuppName"><?= _('Supplier Name') ?>:</label></td>
		<td><input type="text" <?=$attClass['SuppName']?>pattern="(?!^\s+$)[^<>+]{1,40}" required="required" title="<?= _('The supplier name should not be blank and should be less than 40 legal characters') ?>" 
		name="SuppName" value="<?=$_POST['SuppName']??''?>" 
		size="42" placeholder="<?=sprintf(_('Within %d legal characters'),40) ?>" 
		maxlength="40" /></td>
	</tr>
	<tr>
		<td><label for="AddressStreet"><?= _('Street') ?>:</label></td>
		<td><textarea  name="AddressStreet" title="<?= sprintf(_('The input should be less than %d characters'),80) ?>" 
		placeholder="<?=sprintf(_('Less than %d characters'),80) ?>"
		maxlength="80" rows="2" cols="42" ><?=$_POST['AddressStreet']??''?></textarea></td>
	</tr>
	<tr>
		<td><label for="ciudad"><?=_('City') ?>:</label></td>
		<td><input type="text" title="<?=sprintf(_('The input should be less than %d characters'),40) ?>" 
		placeholder="<?=sprintf(_('Less than %d characters'),40) ?>" value="<?=$_POST['ciudad']??''?>"
		name="ciudad" size="42" maxlength="40" /></td>
	</tr>
	<tr>
		<td><label for="estado"><?=_('State') ?>/<?=_('Province') ?>:</label></td>
		<td><input type="text" name="estado" value="<?=$_POST['estado']??''?>" 
			placeholder="<?=sprintf(_('Less than %d characters'),50) ?>" size="42" maxlength="50" /></td>
	</tr>
	<tr>
		<td><label for="cpostal"><?=_('Post Code') ?>:</td>
		<td><input type="text" name="cpostal" size="42" value="<?=$_POST['cpostal']??''?>" 
			placeholder="<?=sprintf(_('Less than %d characters'),5	) ?>" maxlength="40" /></td>
	</tr>
	<tr>
		<td><?=_('Country') ?>:</td>
		<td><select name="country">
			<?php
		foreach ($CountriesArray as $CountryEntry => $CountryName) {
			$isAssignated= (!empty($_POST['country']) and ($_POST['country'] === $CountryName));
			$isDefault=(empty($_POST['country']) and $CountryName == "México");
			$attSelected=($isAssignated || $isDefault)?ATT_SELECTED:'';
			?>
				<option <?=$attSelected?>value="<?=$CountryName ?>"><?=$CountryName ?></option>
		<?php }?>
		</select></td>
	</tr>
	<tr>
		<td><?=_('Telephone No') ?>:</td>
		<td><input type="tel" <?=$attClass['Phone']?>pattern="[\s\d+)(-]{1,40}" title="<?=_('The input should be phone number') ?>" placeholder="<?=_('Phone Number') ?>" name="Phone" value="<?=$_POST['Phone']??''?>"
		 size="30" maxlength="40" /></td>
	</tr>
	<tr>
		<td><?=_('Telephone No') ?> 2:</td>
		<td><input type="tel" <?=$attClass['Phone2']?>pattern="[\s\d+)(-]{1,40}" title="<?=_('The input should be phone number') ?>"
		 placeholder="<?=_('Phone Number') ?>" name="Phone2" value="<?=$_POST['Phone2']??''?>"
		size="30" maxlength="40" /></td>
	</tr>
	<tr>
		<td><?=_('Email Address') ?>:</td>
		<td><input type="email" name="Email" <?=$attClass['Email']?>title="<?=_('Only email address are allowed') ?>" 
			 value="<?=$_POST['Email']??''?>"
			 placeholder="<?=_('email format such as xx@mail.cn') ?>" size="30" maxlength="50" pattern="[a-z0-9!#$%&\'*+/=?^_` {|}~.-]+@[a-z0-9-]+(\.[a-z0-9-]+)*" /></td>
	</tr>
	<tr>
		<td><?=_('Web site') ?>:</td>
		<td><input type="url" name="URL" <?=$attClass['ID']?>title="<?=_('Only URL address are allowed') ?>" 
		placeholder="<?=_('URL format such as http://www.example.com') ?>" value="<?=$_POST['URL']??''?>"
		 size="30" maxlength="50" /></td>
	</tr>
	<tr>
		<td><?=_('Supplier Type') ?>:</td>
		<td><select name="SupplierType">
			<?php
				while ($MyRow = DB_fetch_array($ResultSType)) {
					$isAssignated=$_POST['SupplierType'] == $MyRow['typeid'];
					$isDefault=!isset($_POST['SupplierType']) && (3 == $MyRow['typeid']);
					$attSelected=($isAssignated || $isDefault)?ATT_SELECTED:'';
					?>
					<option <?=$attSelected?>value="<?=$MyRow['typeid'] ?>"><?=$MyRow['typename'] ?></option>
			<?php	} //end while loop?>
		</select></td>
	</tr>

	<tr>
		<td><?=_('Supplier Since') ?> (<?=$_SESSION['DefaultDateFormat'] ?>):</td>
		<td><input type="text" class="date<?=$attClass['SupplierSince']?>" name="SupplierSince" value="<?=$SuppSinceVal ?>" size="11" maxlength="10" /></td>
	</tr>
	<tr>
		<td><?=_('Bank Particulars') ?>:</td>
		<td><input type="text" name="BankPartics" size="13" maxlength="12" value="<?=$_POST['BankPartics']??''?>"/></td>
	</tr>
	<tr>
		<td><?=_('Bank reference') ?>:</td>
		<td><input type="text" name="BankRef" <?=$attClass['BankRef']?>value="<?=$_POST['BankRef']??'0'?>" size="13" maxlength="12" /></td>
	</tr>
	<tr>
		<td><?=_('Bank Account No') ?>:</td>
		<td><input type="text" placeholder="<?=_('Less than 30 characters') ?>" 
			name="BankAct" value="<?=$_POST['BankAct']??''?>" size="31" maxlength="30" /></td></tr>

	<tr>
		<td><?=_('Payment Terms') ?>:</td>
		<td><select name="PaymentTerms">
			<?php
				while ($MyRow = DB_fetch_array($ResultPayTer)) {
					$isPtPost=$_POST['PaymentTerms'] == $MyRow['termsindicator'];
					$isPtDefault=$_POST['PaymentTerms'] == "" and $MyRow['termsindicator']=="CA";
					$attSelected=($isPtPost||$isPtDefault)?ATT_SELECTED:'';
				?>
					<option <?=$attSelected?>value="<?=$MyRow['termsindicator']?>"><?=$MyRow['terms'] ?></option>
			<?php
			} //end while loop
			DB_data_seek($ResultPayTer, 0);
			?>
		</select></td></tr>

	<tr>
		<td><?=_('Tax Reference') ?>:</td>
		<td><input type="text" name="TaxRef" value="<?=$_POST['BankPartics']??''?>"
		placehoder="<?=_('Within 20 characters') ?>" size="21" maxlength="20" /></td></tr>
	<tr>
		<td><?=_('Supplier Currency') ?>:</td>
		<td><input type="hidden" name="CurrCode" value="MXN"/>Peso mexicano</td>
	</tr>
	<tr>
		<td><?=_('Remittance Advice') ?>:</td>
		<td><select name="Remittance">
			<option <?=($_POST['Remittance'] == 0) ? ATT_SELECTED : ''?>value="0"><?=_('Not Required') ?></option>
			<option <?=($_POST['Remittance'] == 1) ? ATT_SELECTED : ''?>value="1"><?=_('Required') ?></option>
			</select></td>
	</tr>
	<tr>
		<td><?=_('Default Shipper') ?>:</td>
		<td>
			<select required="required" name="DefaultShipper">
		<?php
			while ($MyRow = DB_fetch_array($ResultShipper)) {
				$attSelected=($_POST['DefaultShipper'] == $MyRow['shipper_id'])?ATT_SELECTED:'';
				?>
					<option <?=$attSelected?>value="<?=$MyRow['shipper_id'] ?>"><?=$MyRow['shippername'] ?></option>
		<?php
			}
			?>

			</select>
		</td>
	</tr>
	<tr>
		<td><?=_('Default GL Account') ?>:</td>
		<td><select tabindex="19" name="DefaultGL">
		<?php
			while ($MyRow = DB_fetch_row($ResultAccs)) {
				$attSelected=($_POST['DefaultGL'] === $MyRow[0])?ATT_SELECTED:'';
				?>
					<option <?=$attSelected?>value="<?=$MyRow[0] ?>"><?=htmlspecialchars($MyRow[1], ENT_QUOTES,'UTF-8') ?> (<?=$MyRow[0] ?>)</option>
		<?php
			} //end while loop
			DB_data_seek($ResultAccs, 0);
			?>
			</select>
		</td>
	</tr>

	<tr>
			<td><?=_('Tax Group') ?>:</td>
			<td><select name="TaxGroup">

		<?php

			while ($MyRow = DB_fetch_array($ResultTaxG)) {
				$attSelected=(isset($_POST['TaxGroup']) and $_POST['TaxGroup'] == $MyRow['taxgroupid'])? ATT_SELECTED : '';
				?>
					<option <?=$attSelected?>value="<?=$MyRow['taxgroupid'] ?>"><?=$MyRow['taxgroupdescription'] ?></option>
			<?php
			} //end while loop
			?>
			</select></td>
	</tr>
	</table>
	<br />
	<div class="centre"><input type="submit" name="submit" value="<?=$submitText ?>" /></div>
	</div>
	<?php 
	if(!$isNew){
		prnMsg(_('WARNING') . ': ' . _('There is no second warning if you hit the delete button below') . '. ' . _('However checks will be made to ensure there are no outstanding purchase orders or existing accounts payable transactions before the deletion is processed'), 'Warn');
		?>

		<br />
			<div class="centre">
				<input type="submit" name="delete" id='delete' value="<?=_('Delete Supplier') ?>" formnovalidate="formnovalidate"
				onclick="return confirm(<?=_('Are you sure you wish to delete this supplier?') ?>);" />
			</div>
		<?php 
		if($dodelete){
			?>
			<script type="text/javascript">

				window.onload = function() {
					document.getElementById('delete').click();
				};
			</script>
			<?php
		}
	}?>
</form>
	
<?php
include ('includes/footer.php');
?>
