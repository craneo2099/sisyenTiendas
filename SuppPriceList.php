<?php
/*  */

include('includes/session.php');

if (isset($_GET['SelectedSupplier'])) {
	$_POST['supplierid']=$_GET['SelectedSupplier'];
}

if (isset($_POST['PrintPDF']) OR isset($_POST['View'])) {

	include('includes/PDFStarter.php');

	$FontSize=9;
	$pdf->addInfo('Title',_('Supplier Price List'));
	$pdf->addInfo('Subject',_('Price List of goods from a Supplier'));
	
	$FormDesign = simplexml_load_file($PathPrefix . 'companies/' . $_SESSION['DatabaseName'] . '/FormDesigns/suppList.xml');

	$PageNumber=1;
	$line_height=12;

	//get supplier
	$sqlsup = "SELECT suppname,
					  currcode,
					  decimalplaces AS currdecimalplaces
				FROM suppliers INNER JOIN currencies
				ON suppliers.currcode=currencies.currabrev
				WHERE supplierid='" . $_POST['supplier'] . "'";
	$resultsup = DB_query($sqlsup);
	$RowSup = DB_fetch_array($resultsup);
	$SupplierName=$_POST['supplier']." - ".$RowSup['suppname'];
	$CurrCode =$RowSup['currcode'];
	$CurrDecimalPlaces=$RowSup['currdecimalplaces'];

	//get category
	if ($_POST['category']!='all'){
		$sqlcat="SELECT categorydescription
				FROM `stockcategory`
				WHERE categoryid ='" . $_POST['category'] . "'";

		$resultcat = DB_query($sqlcat);
		$RowCat = DB_fetch_row($resultcat);
		$Categoryname=$RowCat['0'];
	} else {
		$Categoryname=_('ALL');
	}


	//get date price
	if ($_POST['price']=='all'){
		$CurrentOrAllPrices=_('All Prices');
	} else {
		$CurrentOrAllPrices=_('Current Price');
	}

	//price and category = all
	if (($_POST['price']=='all') AND ($_POST['category']=='all')){
		$sql = "SELECT 	purchdata.stockid,
					stockmaster.description,
					purchdata.price,
					purchdata.conversionfactor,
					(purchdata.effectivefrom)as dateprice,
					purchdata.supplierdescription,
					purchdata.suppliers_partno
				FROM purchdata,stockmaster
				WHERE supplierno='" . $_POST['supplier'] . "'
				AND stockmaster.stockid=purchdata.stockid
				ORDER BY stockid ASC ,dateprice DESC";
	} else {
	//category=all and price != all
		if (($_POST['price']!='all') AND ($_POST['category']=='all')){

			$sql = "SELECT purchdata.stockid,
							stockmaster.description,
							(SELECT purchdata.price
							 FROM purchdata
							 WHERE purchdata.stockid = stockmaster.stockid
							 ORDER BY effectivefrom DESC
							 LIMIT 0,1) AS price,
							purchdata.conversionfactor,
							(SELECT purchdata.effectivefrom
							 FROM purchdata
							 WHERE purchdata.stockid = stockmaster.stockid
							 ORDER BY effectivefrom DESC
							 LIMIT 0,1) AS dateprice,
							purchdata.supplierdescription,
							purchdata.suppliers_partno
					FROM purchdata, stockmaster
					WHERE supplierno = '" . $_POST['supplier'] . "'
					AND stockmaster.stockid = purchdata.stockid
					GROUP BY stockid
					ORDER BY stockid ASC , dateprice DESC";
		} else {
			//price = all category !=all
			if (($_POST['price']=='all')and($_POST['category']!='all')){

				$sql = "SELECT 	purchdata.stockid,
								stockmaster.description,
								purchdata.price,
								purchdata.conversionfactor,
								(purchdata.effectivefrom)as dateprice,
								purchdata.supplierdescription,
								purchdata.suppliers_partno
						FROM purchdata,stockmaster
						WHERE supplierno='" . $_POST['supplier'] . "'
						AND stockmaster.stockid=purchdata.stockid
						AND stockmaster.categoryid='" . $_POST['category'] .  "'
						ORDER BY stockid ASC ,dateprice DESC";
			} else {
			//price != all category !=all
				$sql = "SELECT 	purchdata.stockid,
								stockmaster.description,
								(SELECT purchdata.price
								 FROM purchdata
								 WHERE purchdata.stockid = stockmaster.stockid
								 ORDER BY effectivefrom DESC
								 LIMIT 0,1) AS price,
								purchdata.conversionfactor,
								(SELECT purchdata.effectivefrom
								FROM purchdata
								WHERE purchdata.stockid = stockmaster.stockid
								ORDER BY effectivefrom DESC
								LIMIT 0,1) AS dateprice,
								purchdata.supplierdescription,
								purchdata.suppliers_partno
						FROM purchdata,stockmaster
						WHERE supplierno='" . $_POST['supplier'] . "'
						AND stockmaster.stockid=purchdata.stockid
						AND stockmaster.categoryid='" . $_POST['category'] .  "'
						GROUP BY stockid
						ORDER BY stockid ASC ,dateprice DESC";
			}
		}
	}
	$result = DB_query($sql,'','',false,true);

	if (DB_error_no() !=0) {
		$Title = _('Price List') . ' - ' . _('Problem Report');
		include('includes/header.php');
		prnMsg( _('The Price List could not be retrieved by the SQL because') . ' '  . DB_error_msg(),'error');
		echo '<br />
				<a href="' .$RootPath .'/index.php">' . _('Back to the menu') . '</a>';
		if ($debug==1){
			echo '<br />' . $sql;
		}
		include('includes/footer.php');
		exit;
	}

	if (DB_num_rows($result)==0) {

		$Title = _('Supplier Price List') . '-' . _('Report');
		include('includes/header.php');
		prnMsg(_('There are no result so the PDF is empty'));
		
		echo '<a href="'.htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8').'">'._('Return').'</a>';
		include('includes/footer.php');
		exit;
	}
	if (!isset($_POST['View'])) {
	PrintHeader($pdf,$YPos,$PageNumber,$Page_Height,$Top_Margin,$Left_Margin,
	            $Page_Width,$Right_Margin,$SupplierName,$Categoryname,$CurrCode,$CurrentOrAllPrices);

	$FontSize=8;
	$code='';
	while ($myrow = DB_fetch_array($result)){
		$YPos -=$line_height;

		$PriceDated=ConvertSQLDate($myrow[4]);

		//if item has more than 1 price, write only price, date and supplier code for the old ones
		if ($code==$myrow['stockid']){

			$pdf->addTextWrap(350,$YPos,50,$FontSize,locale_number_format($myrow['price'],$CurrDecimalPlaces),'right');
			$pdf->addTextWrap(410,$YPos,50,$FontSize,$PriceDated,'left');
			$pdf->addTextWrap(470,$YPos,90,$FontSize,$myrow['suppliers_partno'],'left');
			$code=$myrow['stockid'];
		} else {
			$code=$myrow['stockid'];
			$pdf->addTextWrap(30,$YPos,90,$FontSize,$myrow['stockid'],'left');
			$pdf->addTextWrap(125,$YPos,160,$FontSize,$myrow['description'],'left');
			$pdf->addTextWrap(290,$YPos,80,$FontSize,locale_number_format($myrow['conversionfactor'],'Variable'),'right');
			$pdf->addTextWrap(370,$YPos,50,$FontSize,locale_number_format($myrow['price'],$CurrDecimalPlaces),'right');
			$pdf->addTextWrap(430,$YPos,50,$FontSize,$PriceDated,'left');
			$pdf->addTextWrap(490,$YPos,90,$FontSize,$myrow['suppliers_partno'],'left');
			
		}


		if ($YPos < $Bottom_Margin + $line_height){

			PrintHeader($pdf,$YPos,$PageNumber,$Page_Height,$Top_Margin,$Left_Margin,$Page_Width,
			            $Right_Margin,$SupplierName,$Categoryname,$CurrCode,$CurrentOrAllPrices);
		}


	} /*end while loop  */


	if ($YPos < $Bottom_Margin + $line_height){
	       PrintHeader($pdf,$YPos,$PageNumber,$Page_Height,$Top_Margin,$Left_Margin,$Page_Width,
	                   $Right_Margin,$SupplierName,$Categoryname,$CurrCode,$CurrentOrAllPrices);
	}


	$pdf->OutputD( $_SESSION['CompanyRecord']['coyname'] . '_Proveedor_Lista_' .$RowSup['suppname']. '_' . Date('Y-m-d') . '.pdf');
	} else {
		$Title = _('View supplier price');
		include('includes/header.inc');
		echo '<a href="'.htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8').'">'._('return').'</a>';
		echo '<p class="page_title_text">'. _('Supplier Price List for').' : '.$CurrentOrAllPrices . '<br/>'
			._('Supplier').'   : '.$RowSup['suppname'].' <br/>'._('Category').' : '.$Categoryname.
			'</p>';

		echo '<table class="selection">
			<thead>
				<tr>
					<th class="ascending">' . _('Code') . '</th>
				<th>' . _('Description') . '</th>
				<th>' . _('Conv Factor') . '</th>
				<th>' . _('Price') . '</th>
				<th class="ascending">' . _('Date From') . '</th>
				<th>' . _('Supp Code') . '</th>
				</tr>
			</thead>
			<tbody>';

		while ($myrow = DB_fetch_array($result)){
			echo '<tr class="striped_row">
				<td>' . $myrow['stockid'] . '</td>
				<td>' . $myrow['description'] . '</td>
				<td>' . $myrow['conversionfactor'] . '</td>
				<td>' . $myrow['price'] . '</td>
				<td>' . ConvertSQLDate($myrow['dateprice']) . '</td>
				<td>' . $myrow['suppliers_partno'] . '</td>
				</tr>';

		}

		echo '</tbody></table>';
		include('includes/footer.inc');
	}

} else { /*The option to print PDF was not hit so display form */

	$Title=_('Supplier Price List');
	include('includes/header.php');
	echo '<p class="page_title_text"><img src="'.$RootPath.'/css/'.$Theme.'/images/inventory.png" title="' . _('Purchase') . '" alt="" />' . ' ' . _('Supplier Price List') . '</p>';
	echo '<div class="page_help_text">' . _('View the Price List from supplier') . '</div><br />';

	echo '<br/>
		<form action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '" method="post">';
    echo '<div>';
    echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

	$sql = "SELECT supplierid,suppname FROM `suppliers`";
	$result = DB_query($sql);
	echo '<table class="selection">
			<tr>
				<td>' . _('Supplier') . ':</td>
				<td><select name="supplier"> ';
	while ($myrow=DB_fetch_array($result)){
		if (isset($_POST['supplierid']) and ($myrow['supplierid'] == $_POST['supplierid'])) {
			 echo '<option selected="selected" value="' . $myrow['supplierid'] . '">' . $myrow['supplierid'].' - '.$myrow['suppname'] . '</option>';
		} else {
			 echo '<option value="' . $myrow['supplierid'] . '">' . $myrow['supplierid'].' - '.$myrow['suppname'] . '</option>';
		}
	}
	echo '</select></td>
		</tr>';

	$sql="SELECT categoryid, categorydescription FROM stockcategory";
	$result = DB_query($sql);
	echo '<tr>
			<td>' . _('Category') . ':</td>
			<td><select name="category"> ';
		echo '<option value="all">' . _('ALL') . '</option>';
	while ($myrow=DB_fetch_array($result)){
		if (isset($_POST['categoryid']) and ($myrow['categoryid'] == $_POST['categoryid'])) {
			 echo '<option selected="selected" value="' . $myrow['categoryid'] . '">' . $myrow['categoryid']-$myrow['categorydescription'] . '</option>';
		} else {
			 echo '<option value="' . $myrow['categoryid'] . '">' .$myrow['categoryid'].' - '. $myrow['categorydescription'] . '</option>';
		}
	}
	echo '</select></td>
		</tr>';
	echo '<tr>
			<td>' . _('Price List') . ':</td>
			<td><select name="price">
				<option value="all">' ._('All Prices') . '</option>
				<option value="current">' ._('Only Current Price') . '</option>
				</select>
			</td>
		</tr>';
	echo '</table>
			<br/>
			<div class="centre">
				<input type="submit" name="PrintPDF" value="' . _('Print PDF') . '" />
			</div>';

    echo '</div>
          </form>';
	include('includes/footer.php');

} /*end of else not PrintPDF */



function PrintHeader(&$pdf,&$YPos,&$PageNumber,$Page_Height,$Top_Margin,$Left_Margin,
                     $Page_Width,$Right_Margin,$SupplierName,$Categoryname,$CurrCode,$CurrentOrAllPrices) {

	global $FormDesign;					
	/*PDF page header for Supplier price list */
	if ($PageNumber>1){
		$pdf->newPage();
	}
	$line_height=12;
	$FontSize=9;
	$YPos= $Page_Height-$Top_Margin;
	$pdf->addJpegFromFile($_SESSION['LogoFile'],$Left_Margin+$FormDesign->logo->x,$Page_Height- $FormDesign->logo->y,$FormDesign->logo->width,$FormDesign->logo->height);
	$pdf->addText($FormDesign->CompanyName->x,$Page_Height - $FormDesign->CompanyName->y, $FormDesign->CompanyName->FontSize,$_SESSION['CompanyRecord']['coyname']);
	$pdf->addText($FormDesign->OrderNumber->x,$Page_Height- $FormDesign->OrderNumber->y,$FormDesign->OrderNumber->FontSize, _('Supplier Price List for'). ' ' . $CurrentOrAllPrices);
	
	$pdf->addText($FormDesign->PageNumber->x,$Page_Height - $FormDesign->PageNumber->y, $FormDesign->PageNumber->FontSize, _('Printed') . ': ' .
		Date($_SESSION['DefaultDateFormat']) . '   ' . _('Page') . ' ' . $PageNumber);
		
	$pdf->addText($FormDesign->SupplierName->x,$Page_Height - $FormDesign->SupplierName->y, 
		$FormDesign->SupplierName->FontSize, _('Supplier').'   : '.$SupplierName);
	$pdf->addText($FormDesign->Category->x,$Page_Height - $FormDesign->Category->y,$FormDesign->Category->FontSize,
		_('Category').' : '.$Categoryname);	
	$pdf->addText($FormDesign->Currency->x,$Page_Height - $FormDesign->Currency->y,$FormDesign->Currency->FontSize,
		_('All amounts stated in').' - ' . $CurrCode . ' ' . $CurrencyName[$CurrCode]);

	$pdf->Rectangle($FormDesign->HeaderRectangle->x, $Page_Height - $FormDesign->HeaderRectangle->y, $FormDesign->HeaderRectangle->width,$FormDesign->HeaderRectangle->height);
	$YPos -=(11*$line_height);
	/*set up the headings */
	
	$pdf->addTextWrap(30,$YPos,70,$FontSize,_('Code'), 'left');
	$pdf->addTextWrap(125,$YPos,80,$FontSize,_('Description'), 'left');
	$pdf->addTextWrap(290,$YPos,80,$FontSize,_('Conv Factor'), 'left');
	$pdf->addTextWrap(390,$YPos,50,$FontSize,_('Price'), 'left');
	$pdf->addTextWrap(430,$YPos,80,$FontSize,_('Date From'), 'left');
	$pdf->addTextWrap(490,$YPos,80,$FontSize,_('Supp Code'), 'left');
		
	$pdf->Rectangle($FormDesign->DataRectangle->x, $Page_Height - $FormDesign->DataRectangle->y, $FormDesign->DataRectangle->width,$FormDesign->DataRectangle->height);
	$FontSize=8;
	$PageNumber++;
	$YPos -=(1*$line_height);
} // End of PrintHeader() function
?>
