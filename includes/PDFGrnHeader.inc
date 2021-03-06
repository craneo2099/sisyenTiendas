<?php
/*PDF page header for price list report */
if ($PageNumber>1){
	$pdf->newPage();
}

$pdf->addJpegFromFile($_SESSION['LogoFile'] ,$FormDesign->logo->x,$Page_Height-$FormDesign->logo->y,$FormDesign->logo->width,$FormDesign->logo->height);

$LeftOvers = $pdf->addText($FormDesign->SupplierName->x,$Page_Height-$FormDesign->SupplierName->y,$FormDesign->SupplierName->FontSize,$SuppRow['suppname']);
$suppAddress=implode_list($SuppRow['address1'],$SuppRow['address2'],$SuppRow['address3'],$SuppRow['address4'],$SuppRow['address5'],$SuppRow['address6']);
$pdf->MultiCell($FormDesign->SupplierAddress->w,0,$suppAddress,0, 'L', false, 0, 
$FormDesign->SupplierAddress->x, $FormDesign->SupplierAddress->y);


$LeftOvers = $pdf->addText($FormDesign->CompanyName->x,$Page_Height-$FormDesign->CompanyName->y,$FormDesign->CompanyName->FontSize,$_SESSION['CompanyRecord']['coyname']);
$LeftOvers = $pdf->addText($FormDesign->GRNNumber->x,$Page_Height-$FormDesign->GRNNumber->y,$FormDesign->GRNNumber->FontSize, _('GRN number').' ' . $GRNNo );
$LeftOvers = $pdf->addText($FormDesign->SupplierRef->x,$Page_Height-$FormDesign->SupplierRef->y,$FormDesign->SupplierRef->FontSize,_("Supplier's Ref") . ' ' . $SupplierRef);
$LeftOvers = $pdf->addText($FormDesign->OrderNumber->x,$Page_Height-$FormDesign->OrderNumber->y,$FormDesign->OrderNumber->FontSize, _('PO number ').' ' . $_GET['PONo'] );
$LeftOvers = $pdf->addText($FormDesign->PrintDate->x,$Page_Height-$FormDesign->PrintDate->y,$FormDesign->PrintDate->FontSize, _('Printed').': ' . Date($_SESSION['DefaultDateFormat']) . '   '. _('Page'). ' ' . $PageNumber);

/*Draw a rectangle to put the headings in     */
$pdf->Rectangle($FormDesign->HeaderRectangle->x, $Page_Height - $FormDesign->HeaderRectangle->y, $FormDesign->HeaderRectangle->width,$FormDesign->HeaderRectangle->height);

/*set up the headings */
$LeftOvers = $pdf->MultiCell(65,0,_('Item Number'),0, 'C', false, 0, 
	$FormDesign->Headings->Column1->x, $FormDesign->Headings->Column1->y-($FormDesign->Headings->Column2->FontSize+2));
$LeftOvers = $pdf->addText($FormDesign->Headings->Column2->x,$Page_Height - $FormDesign->Headings->Column2->y, $FormDesign->Headings->Column2->FontSize,  _('Description'));
/*resmart mods*/
$LeftOvers = $pdf->addText($FormDesign->Headings->Column3->x,$Page_Height - $FormDesign->Headings->Column3->y, $FormDesign->Headings->Column3->FontSize,  str_pad(_('Date Recd'),22,' ',STR_PAD_LEFT));
//$LeftOvers = $pdf->addTextWrap($FormDesign->Headings->Column3->x,$Page_Height - $FormDesign->Headings->Column3->y, $FormDesign->Headings->Column4->Length, $FormDesign->Headings->Column3->FontSize,  _('Date Recd'), 'right');
/*resmart ends*/

$LeftOvers = $pdf->MultiCell(130,0, _('Qty in Suppliers UOM'),0, 'C', false, 0, 
	$FormDesign->Headings->Column4->x, $FormDesign->Headings->Column4->y-($FormDesign->Headings->Column4->FontSize+2));

$LeftOvers = $pdf->MultiCell(160,0,_('Qty in Stock UOM'),0, 'C', false, 0, 
	$FormDesign->Headings->Column5->x, $FormDesign->Headings->Column5->y);

/*Draw a rectangle to put the data in     */
$pdf->Rectangle($FormDesign->DataRectangle->x, $Page_Height - $FormDesign->DataRectangle->y, $FormDesign->DataRectangle->width,$FormDesign->DataRectangle->height);

$pdf->Line($FormDesign->LineAboveFooter->startx, $Page_Height - $FormDesign->LineAboveFooter->starty, $FormDesign->LineAboveFooter->endx,$Page_Height - $FormDesign->LineAboveFooter->endy);

$pdf->Line($FormDesign->Column1->startx, $Page_Height - $FormDesign->Column1->starty, $FormDesign->Column1->endx,$Page_Height - $FormDesign->Column1->endy);
$pdf->Line($FormDesign->Column3->startx, $Page_Height - $FormDesign->Column3->starty, $FormDesign->Column3->endx,$Page_Height - $FormDesign->Column3->endy);
$pdf->Line($FormDesign->Column4->startx, $Page_Height - $FormDesign->Column4->starty, $FormDesign->Column4->endx,$Page_Height - $FormDesign->Column4->endy);
$pdf->Line($FormDesign->Column5->startx, $Page_Height - $FormDesign->Column5->starty, $FormDesign->Column5->endx,$Page_Height - $FormDesign->Column5->endy);

$PageNumber++;
?>
