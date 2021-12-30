<?php

require_once($PathPrefix .'includes/devstar/novatech.php');


include('includes/PDFStarter.php');

$FontSize=9;
$pdf->addInfo('Title',$Title);
$pdf->addInfo('Subject',_('Search all purchases in selected period'));

$FormDesign = simplexml_load_file($PathPrefix . 'companies/' . $_SESSION['DatabaseName'] . '/FormDesigns/suppPurch.xml');

$PageNumber=1;
$line_height=12;
$Data=array(
    'titulo'=>$Title,
    'periodFrom'=>$PeriodFrom,
    'periodTo'=>$PeriodTo,
    'headers'=>$tableHeaders
);
prPrintHeader($pdf,$YPos,$PageNumber,$Page_Height,$Top_Margin,$Left_Margin,
$Page_Width,$Right_Margin,$Data);

$FontSize=8;
$SupplierId='';

$TotalGlAmount = 0;
$TotalGlTax = 0;
if($_POST['ShowDetails']) {
    while ($MyRow = DB_fetch_array($Result)){

        $SupplierId = '';
        $SupplierOvAmount = 0;
        $SupplierOvTax = 0;
        $SupplierGlAmount = 0;
        $SupplierGlTax = 0;
        $columns=array();
        if($MyRow['supplierno'] != $SupplierId) {// If different, prints supplier totals:
            if($SupplierId != '') {// If NOT the first line.

                $columns[3]=locale_number_format($SupplierOvAmount, $_SESSION['CompanyRecord']['decimalplaces']);
                $columns[4]=locale_number_format($SupplierOvTax, $_SESSION['CompanyRecord']['decimalplaces']);
                $columns[5]=locale_number_format($SupplierOvAmount+$SupplierOvTax, $_SESSION['CompanyRecord']['decimalplaces']);
                $columns[6]=locale_number_format($SupplierGlAmount, $_SESSION['CompanyRecord']['decimalplaces']);
                $columns[7]=locale_number_format($SupplierGlTax, $_SESSION['CompanyRecord']['decimalplaces']);
                $columns[8]=locale_number_format($SupplierGlAmount+$SupplierGlTax, $_SESSION['CompanyRecord']['decimalplaces']);
                $pdf->addTextWrap($FormDesign->Data->Column4->x, $YPos, $FormDesign->Data->Column4->Length, $FormDesign->Data->Column4->FontSize, $columns[3], 'right');
                $pdf->addTextWrap($FormDesign->Data->Column5->x, $YPos, $FormDesign->Data->Column5->Length, $FormDesign->Data->Column5->FontSize, $columns[4], 'right');
                $pdf->addTextWrap($FormDesign->Data->Column6->x, $YPos, $FormDesign->Data->Column6->Length, $FormDesign->Data->Column6->FontSize, $columns[5], 'right');
                $pdf->addTextWrap($FormDesign->Data->Column7->x, $YPos, $FormDesign->Data->Column7->Length, $FormDesign->Data->Column7->FontSize, $columns[6], 'right');
                $pdf->addTextWrap($FormDesign->Data->Column8->x, $YPos, $FormDesign->Data->Column8->Length, $FormDesign->Data->Column8->FontSize, $columns[7], 'right');
                $pdf->addTextWrap($FormDesign->Data->Column9->x, $YPos, $FormDesign->Data->Column9->Length, $FormDesign->Data->Column9->FontSize, $columns[8], 'right');
                $YPos -=$line_height;
            }
            $columns[0]=$MyRow['supplierno']. ' - '.$MyRow['suppname'];
            $pdf->addText($FormDesign->Data->Column1->x,$YPos+$line_height, $FormDesign->Data->Column1->FontSize, $columns[0] );
            $YPos -=$line_height;
            $TotalGlAmount += $SupplierGlAmount;
            $TotalGlTax += $SupplierGlTax;
            $SupplierId = $MyRow['supplierno'];
            $SupplierOvAmount = 0;
            $SupplierOvTax = 0;
            $SupplierGlAmount = 0;
            $SupplierGlTax = 0;
        }

        $GlAmount = $MyRow['ovamount']/$MyRow['rate'];
        $GlTax = $MyRow['ovgst']/$MyRow['rate'];

            
        $columns[0]=$MyRow['trandate'];
        $columns[1]=$MyRow['transno'];
        $columns[2]=$MyRow['suppreference'];
        $columns[3]=locale_number_format($MyRow['ovamount'], $_SESSION['CompanyRecord']['decimalplaces']);
        $columns[4]=locale_number_format($MyRow['ovgst'], $_SESSION['CompanyRecord']['decimalplaces']);
        $columns[5]=locale_number_format($MyRow['ovamount']+$MyRow['ovgst'], $_SESSION['CompanyRecord']['decimalplaces']);
        $columns[6]=locale_number_format($GlAmount, $_SESSION['CompanyRecord']['decimalplaces']);
        $columns[7]=locale_number_format($GlTax, $_SESSION['CompanyRecord']['decimalplaces']);
        $columns[8]=locale_number_format($GlAmount+$GlTax, $_SESSION['CompanyRecord']['decimalplaces']);

        $pdf->addTextWrap($FormDesign->Data->Column1->x, $YPos, $FormDesign->Data->Column1->Length, $FormDesign->Data->Column1->FontSize, $columns[0], 'left');
        $pdf->addTextWrap($FormDesign->Data->Column2->x, $YPos, $FormDesign->Data->Column2->Length, $FormDesign->Data->Column2->FontSize, $columns[1], 'center');
        $pdf->addTextWrap($FormDesign->Data->Column3->x, $YPos, $FormDesign->Data->Column3->Length, $FormDesign->Data->Column3->FontSize, $columns[2], 'center');
        $pdf->addTextWrap($FormDesign->Data->Column4->x, $YPos, $FormDesign->Data->Column4->Length, $FormDesign->Data->Column4->FontSize, $columns[3], 'right');
        $pdf->addTextWrap($FormDesign->Data->Column5->x, $YPos, $FormDesign->Data->Column5->Length, $FormDesign->Data->Column5->FontSize, $columns[4], 'right');
        $pdf->addTextWrap($FormDesign->Data->Column6->x, $YPos, $FormDesign->Data->Column6->Length, $FormDesign->Data->Column6->FontSize, $columns[5], 'right');
        $pdf->addTextWrap($FormDesign->Data->Column7->x, $YPos, $FormDesign->Data->Column7->Length, $FormDesign->Data->Column7->FontSize, $columns[6], 'right');
        $pdf->addTextWrap($FormDesign->Data->Column8->x, $YPos, $FormDesign->Data->Column8->Length, $FormDesign->Data->Column8->FontSize, $columns[7], 'right');
        $pdf->addTextWrap($FormDesign->Data->Column9->x, $YPos, $FormDesign->Data->Column9->Length, $FormDesign->Data->Column9->FontSize, $columns[8], 'right');
            
        $SupplierOvAmount += $MyRow['ovamount'];
        $SupplierOvTax += $MyRow['ovgst'];
        $SupplierGlAmount += $GlAmount;
        $SupplierGlTax += $GlTax;


        if ($YPos < $Bottom_Margin + $line_height){

            prPrintHeader($pdf,$YPos,$PageNumber,$Page_Height,$Top_Margin,$Left_Margin,$Page_Width,
                $Right_Margin,$SupplierName,$Categoryname,$CurrCode,$CurrentOrAllPrices);
        }
        $YPos -=$line_height;

    } /*end while loop  */
    $columns[3]=locale_number_format($SupplierOvAmount, $_SESSION['CompanyRecord']['decimalplaces']);
    $columns[4]=locale_number_format($SupplierOvTax, $_SESSION['CompanyRecord']['decimalplaces']);
    $columns[5]=locale_number_format($SupplierOvAmount+$SupplierOvTax, $_SESSION['CompanyRecord']['decimalplaces']);
    $columns[6]=locale_number_format($SupplierGlAmount, $_SESSION['CompanyRecord']['decimalplaces']);
    $columns[7]=locale_number_format($SupplierGlTax, $_SESSION['CompanyRecord']['decimalplaces']);
    $columns[8]=locale_number_format($SupplierGlAmount+$SupplierGlTax, $_SESSION['CompanyRecord']['decimalplaces']);
    $pdf->addTextWrap($FormDesign->Data->Column4->x, $YPos, $FormDesign->Data->Column4->Length, $FormDesign->Data->Column4->FontSize, $columns[3], 'right');
    $pdf->addTextWrap($FormDesign->Data->Column5->x, $YPos, $FormDesign->Data->Column5->Length, $FormDesign->Data->Column5->FontSize, $columns[4], 'right');
    $pdf->addTextWrap($FormDesign->Data->Column6->x, $YPos, $FormDesign->Data->Column6->Length, $FormDesign->Data->Column6->FontSize, $columns[5], 'right');
    $pdf->addTextWrap($FormDesign->Data->Column7->x, $YPos, $FormDesign->Data->Column7->Length, $FormDesign->Data->Column7->FontSize, $columns[6], 'right');
    $pdf->addTextWrap($FormDesign->Data->Column8->x, $YPos, $FormDesign->Data->Column8->Length, $FormDesign->Data->Column8->FontSize, $columns[7], 'right');
    $pdf->addTextWrap($FormDesign->Data->Column9->x, $YPos, $FormDesign->Data->Column9->Length, $FormDesign->Data->Column9->FontSize, $columns[8], 'right');
    $TotalGlAmount += $SupplierGlAmount;
    $TotalGlTax += $SupplierGlTax;
    $YPos -=$line_height;
} else {// Parameters: PeriodFrom, PeriodTo, ShowDetails=off.


    while ($MyRow = DB_fetch_array($Result)){
        $columns[0]=$MyRow['supplierno'];
        $columns[1]=$MyRow['suppname'];
        $columns[2]=$MyRow['currcode'];
        $columns[3]=locale_number_format($MyRow['SupplierOvAmount'], $_SESSION['CompanyRecord']['decimalplaces']);
        $columns[4]=locale_number_format($MyRow['SupplierOvTax'], $_SESSION['CompanyRecord']['decimalplaces']);
        $columns[5]=locale_number_format($MyRow['SupplierOvAmount']+$MyRow['SupplierOvTax'], $_SESSION['CompanyRecord']['decimalplaces']);
        $columns[6]=locale_number_format($MyRow['SupplierGlAmount'], $_SESSION['CompanyRecord']['decimalplaces']);
        $columns[7]=locale_number_format($MyRow['SupplierGlTax'], $_SESSION['CompanyRecord']['decimalplaces']);
        $columns[8]=locale_number_format($MyRow['SupplierGlAmount']+$MyRow['SupplierGlTax'], $_SESSION['CompanyRecord']['decimalplaces']);

        $pdf->addTextWrap($FormDesign->Data->Column1->x, $YPos, $FormDesign->Data->Column1->Length, $FormDesign->Data->Column1->FontSize, $columns[0], 'left');
        $pdf->addTextWrap($FormDesign->Data->Column2->x, $YPos, $FormDesign->Data->Column2->Length, $FormDesign->Data->Column2->FontSize, $columns[1], 'center');
        $pdf->addTextWrap($FormDesign->Data->Column3->x, $YPos, $FormDesign->Data->Column3->Length, $FormDesign->Data->Column3->FontSize, $columns[2], 'center');
        $pdf->addTextWrap($FormDesign->Data->Column4->x, $YPos, $FormDesign->Data->Column4->Length, $FormDesign->Data->Column4->FontSize, $columns[3], 'right');
        $pdf->addTextWrap($FormDesign->Data->Column5->x, $YPos, $FormDesign->Data->Column5->Length, $FormDesign->Data->Column5->FontSize, $columns[4], 'right');
        $pdf->addTextWrap($FormDesign->Data->Column6->x, $YPos, $FormDesign->Data->Column6->Length, $FormDesign->Data->Column6->FontSize, $columns[5], 'right');
        $pdf->addTextWrap($FormDesign->Data->Column7->x, $YPos, $FormDesign->Data->Column7->Length, $FormDesign->Data->Column7->FontSize, $columns[6], 'right');
        $pdf->addTextWrap($FormDesign->Data->Column8->x, $YPos, $FormDesign->Data->Column8->Length, $FormDesign->Data->Column8->FontSize, $columns[7], 'right');
        $pdf->addTextWrap($FormDesign->Data->Column9->x, $YPos, $FormDesign->Data->Column9->Length, $FormDesign->Data->Column9->FontSize, $columns[8], 'right');
              
        $TotalGlAmount += $MyRow['SupplierGlAmount'];
        $TotalGlTax += $MyRow['SupplierGlTax'];

    	$YPos -=$line_height;
    }
}

$columns[6]=locale_number_format($TotalGlAmount, $_SESSION['CompanyRecord']['decimalplaces']);
$columns[7]=locale_number_format($TotalGlTax, $_SESSION['CompanyRecord']['decimalplaces']);
$columns[8]=locale_number_format($TotalGlAmount+$TotalGlTax, $_SESSION['CompanyRecord']['decimalplaces']);
$pdf->addTextWrap($FormDesign->Data->Column7->x, $YPos, $FormDesign->Data->Column7->Length, $FormDesign->Data->Column7->FontSize, $columns[6], 'right');
$pdf->addTextWrap($FormDesign->Data->Column8->x, $YPos, $FormDesign->Data->Column8->Length, $FormDesign->Data->Column8->FontSize, $columns[7], 'right');
$pdf->addTextWrap($FormDesign->Data->Column9->x, $YPos, $FormDesign->Data->Column9->Length, $FormDesign->Data->Column9->FontSize, $columns[8], 'right');


if ($YPos < $Bottom_Margin + $line_height){
    prPrintHeader($pdf,$YPos,$PageNumber,$Page_Height,$Top_Margin,$Left_Margin,$Page_Width,
        $Right_Margin,$SupplierName,$Categoryname,$CurrCode,$CurrentOrAllPrices);
}

$pdf->OutputD( $_SESSION['CompanyRecord']['coyname'] . '_Proveedor_Lista_' .$RowSup['suppname']. '_' . Date('Y-m-d') . '.pdf');


function prPrintHeader(&$pdf,&$YPos,&$PageNumber,$Page_Height,$Top_Margin,$Left_Margin,
                     $Page_Width,$Right_Margin,$data) {

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
	$pdf->addText($FormDesign->OrderNumber->x,$Page_Height- $FormDesign->OrderNumber->y,$FormDesign->OrderNumber->FontSize, $data['titulo']);
	$pdf->addText($FormDesign->PageNumber->x,$Page_Height - $FormDesign->PageNumber->y, $FormDesign->PageNumber->FontSize, _('Printed') . ': ' .
		Date($_SESSION['DefaultDateFormat']) . '   ' . _('Page') . ' ' . $PageNumber);
		
	$pdf->addText($FormDesign->SupplierName->x,$Page_Height - $FormDesign->SupplierName->y, 
		$FormDesign->SupplierName->FontSize, _('Supplier').'   : '.$SupplierName);
    if(isset($data['Categoryname'])){
        $pdf->addText($FormDesign->Category->x,$Page_Height - $FormDesign->Category->y,$FormDesign->Category->FontSize,
            _('Category').' : '.$data['Categoryname']);	
    }
    
    if(isset($data['periodFrom'])){
        $pdf->addText($FormDesign->periodFrom->x,$Page_Height - $FormDesign->periodFrom->y,$FormDesign->periodFrom->FontSize,
            _('Period from').' : '.$data['periodFrom']);	
            $pdf->addText($FormDesign->periodTo->x,$Page_Height - $FormDesign->periodTo->y,$FormDesign->periodTo->FontSize,
                _('Period to').' : '.$data['periodTo']);	
    }
    
    
    if(isset($data['CurrCode'])){
        include($PathPrefix . 'includes/CurrenciesArray.php'); // To get the currency name from the currency code.
        $pdf->addText($FormDesign->Currency->x,$Page_Height - $FormDesign->Currency->y,$FormDesign->Currency->FontSize,
            _('All amounts stated in').' - ' . $data['CurrCode'] . ' ' . $CurrencyName[$CurrCode]);
    }
    $pdf->SetFontSize($FormDesign->Headings->Column1->FontSize);
	$pdf->Rectangle($FormDesign->HeaderRectangle->x, $Page_Height - $FormDesign->HeaderRectangle->y, $FormDesign->HeaderRectangle->width,$FormDesign->HeaderRectangle->height);
		/*set up the headings */
	$pdf->MultiCell($FormDesign->Headings->Column1->w,0,$data['headers'][0],0, 'C', false, 0, 
        $FormDesign->Headings->Column1->x, $FormDesign->Headings->Column1->y);
	$pdf->MultiCell($FormDesign->Headings->Column2->w,0,$data['headers'][1],0, 'C', false, 0, 
        $FormDesign->Headings->Column2->x, $FormDesign->Headings->Column2->y);
	$pdf->MultiCell($FormDesign->Headings->Column3->w,0,$data['headers'][2],0, 'C', false, 0, 
        $FormDesign->Headings->Column3->x, $FormDesign->Headings->Column3->y);
	$pdf->MultiCell($FormDesign->Headings->Column4->w,0,$data['headers'][3],0, 'C', false, 0, 
        $FormDesign->Headings->Column4->x, $FormDesign->Headings->Column4->y);
	$pdf->MultiCell($FormDesign->Headings->Column5->w,0,$data['headers'][4],0, 'C', false, 0, 
        $FormDesign->Headings->Column5->x, $FormDesign->Headings->Column5->y);
	$pdf->MultiCell($FormDesign->Headings->Column6->w,0,$data['headers'][5],0, 'C', false, 0, 
        $FormDesign->Headings->Column6->x, $FormDesign->Headings->Column6->y);
	$pdf->MultiCell($FormDesign->Headings->Column7->w,0,$data['headers'][6],0, 'C', false, 0, 
        $FormDesign->Headings->Column7->x, $FormDesign->Headings->Column7->y);
	$pdf->MultiCell($FormDesign->Headings->Column8->w,0,$data['headers'][7],0, 'C', false, 0, 
        $FormDesign->Headings->Column8->x, $FormDesign->Headings->Column8->y);
	$pdf->MultiCell($FormDesign->Headings->Column9->w,0,$data['headers'][8],0, 'C', false, 0, 
        $FormDesign->Headings->Column9->x, $FormDesign->Headings->Column9->y);
   
	$pdf->Rectangle($FormDesign->DataRectangle->x, $Page_Height - $FormDesign->DataRectangle->y, $FormDesign->DataRectangle->width,$FormDesign->DataRectangle->height);
	$FontSize=8;
	$PageNumber++;
	$YPos =$Page_Height - $FormDesign->Data->y;
} // End of PrintHeader() function
?>
 