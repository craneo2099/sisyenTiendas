<?php
function pdfGeneralHead(&$pdf,&$YPos,&$PageNumber,$Page_Height,$Top_Margin,$Left_Margin,
    $Page_Width,$Right_Margin,$data) {
    global $PathPrefix;

    $FormDesign = simplexml_load_file($PathPrefix . 'companies/' . $_SESSION['DatabaseName'] . '/FormDesigns/GeneralHead_portrait.xml');;					
    /*PDF page header for Supplier price list */
    if ($PageNumber>1){
        $pdf->newPage();
    }
    $line_height=12;
    $FontSize=9;
    $YPos= $Page_Height-$Top_Margin;
    $pdf->addJpegFromFile($_SESSION['LogoFile'],$Left_Margin+$FormDesign->logo->x,$Page_Height- $FormDesign->logo->y,$FormDesign->logo->width,$FormDesign->logo->height);
    $pdf->addText($FormDesign->CompanyName->x,$Page_Height - $FormDesign->CompanyName->y, $FormDesign->CompanyName->FontSize,$_SESSION['CompanyRecord']['coyname']);
    $pdf->SetFontSize($FormDesign->OrderNumber->FontSize);
    $pdf->MultiCell($FormDesign->OrderNumber->w,0,$data['titulo'],0, 'L', false, 0, 
	    $FormDesign->OrderNumber->x, $FormDesign->OrderNumber->y);
    
    $pdf->addText($FormDesign->PageNumber->x,$Page_Height - $FormDesign->PageNumber->y, $FormDesign->PageNumber->FontSize, _('Printed') . ': ' .
    Date($_SESSION['DefaultDateFormat']) . '   ' . _('Page') . ' ' . $PageNumber);

    if(isset($data['SupplierName'])){
        $pdf->addText($FormDesign->SupplierName->x,$Page_Height - $FormDesign->SupplierName->y, 
        $FormDesign->SupplierName->FontSize, _('Supplier').'   : '.$SupplierName);
    }
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
} // End of PrintHeader() function