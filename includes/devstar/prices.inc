<?php 
function ReSequenceEffectiveDates ($Item, $PriceList, $CurrAbbrev) {

	/*This is quite complicated - the idea is that prices set up should be unique and there is no way two prices could be returned as valid - when getting a price in includes/GetPrice.inc the logic is to first look for a price of the salestype/currency within the effective start and end dates - then if not get the price with a start date prior but a blank end date (the default price). We would not want two prices where one price falls inside another effective date range except in the case of a blank end date - ie no end date - the default price for the currency/salestype.
	I first thought that we would need to update the previous default price (blank end date), when a new default price is entered, to have an end date of the startdate of this new default price less 1 day - but this is  converting a default price into a special price which could result in having two special prices over the same date range - best to leave it unchanged and use logic in the GetPrice.inc to ensure the correct default price is returned
	*
	* After further discussion (Ricard) if the new price has a blank end date - i.e. no end then the pre-existing price with no end date should be changed to have an end date just prior to the new default (no end date) price commencing
	*/
	//this is just the case where debtorno='' - see the Prices_Customer.php script for customer special prices
		$SQL = "SELECT price,
						startdate,
						enddate
				FROM prices
				WHERE debtorno=''
				AND stockid='" . $Item . "'
				AND currabrev='" . $CurrAbbrev . "'
				AND typeabbrev='" . $PriceList . "'
				AND enddate <>'9999-12-31'
				ORDER BY startdate, enddate";
		$result = DB_query($SQL);

		while ($myrow = DB_fetch_array($result)){
			if (isset($NextStartDate)){
				if (Date1GreaterThanDate2(ConvertSQLDate($myrow['startdate']),$NextStartDate)){
					$NextStartDate = ConvertSQLDate($myrow['startdate']);
					//Only if the previous enddate is after the new start date do we need to look at updates
					if (Date1GreaterThanDate2(ConvertSQLDate($EndDate),ConvertSQLDate($myrow['startdate']))) {
						/*Need to make the end date the new start date less 1 day */
						$SQL = "UPDATE prices SET enddate = '" . FormatDateForSQL(DateAdd($NextStartDate,'d',-1))  . "'
										WHERE stockid ='" .$Item . "'
										AND currabrev='" . $CurrAbbrev . "'
										AND typeabbrev='" . $PriceList . "'
										AND startdate ='" . $StartDate . "'
										AND enddate = '" . $EndDate . "'
										AND debtorno =''";
						$UpdateResult = DB_query($SQL);
					}
				} //end of if startdate  after NextStartDate - we have a new NextStartDate
			} //end of if set NextStartDate
				else {
					$NextStartDate = ConvertSQLDate($myrow['startdate']);
			}
			$StartDate = $myrow['startdate'];
			$EndDate = $myrow['enddate'];
		} // end of loop around all prices

		//Now look for duplicate prices with no end
		$SQL = "SELECT price,
						startdate,
						enddate
					FROM prices
					WHERE debtorno=''
					AND stockid='" . $Item . "'
					AND currabrev='" . $CurrAbbrev . "'
					AND typeabbrev='" . $PriceList . "'
					AND enddate ='9999-12-31'
					ORDER BY startdate";
		$result = DB_query($SQL);

		while ($myrow = DB_fetch_array($result)) {
			if (isset($OldStartDate)){
			/*Need to make the end date the new start date less 1 day */
				$NewEndDate = FormatDateForSQL(DateAdd(ConvertSQLDate($myrow['startdate']),'d',-1));
				$SQL = "UPDATE prices SET enddate = '" . $NewEndDate  . "'
							WHERE stockid ='" .$Item . "'
							AND currabrev='" . $CurrAbbrev . "'
							AND typeabbrev='" . $PriceList . "'
							AND startdate ='" . $OldStartDate . "'
							AND enddate = '9999-12-31'
							AND debtorno =''";
				$UpdateResult = DB_query($SQL);
			}
			$OldStartDate = $myrow['startdate'];
		} // end of loop around duplicate no end date prices

} // end function ReSequenceEffectiveDates
