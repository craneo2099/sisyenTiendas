<?php
/*PDF page header for inventory valuation report */
if ($PageNumber>1){
	$pdf->newPage();
}


/*Draw a rectangle to put the headings in     */

$pdf->line($Left_Margin, $YPos+$line_height,$Page_Width-$Right_Margin, $YPos+$line_height);
$pdf->line($Left_Margin, $YPos+$line_height,$Left_Margin, $YPos- $line_height);
$pdf->line($Left_Margin, $YPos- $line_height,$Page_Width-$Right_Margin, $YPos- $line_height);
$pdf->line($Page_Width-$Right_Margin, $YPos+$line_height,$Page_Width-$Right_Margin, $YPos- $line_height);

/*set up the headings */
$Xpos = $Left_Margin+1;

if ($_POST['DetailedReport']=='Yes'){

	$LeftOvers = $pdf->addTextWrap($Xpos,$YPos,300-$Left_Margin,$FontSize,_('Category') . '/' . _('Item'), 'left');
	$LeftOvers = $pdf->addTextWrap(360,$YPos,60,$FontSize,_('Quantity'), 'center');
	$LeftOvers = $pdf->addTextWrap(422,$YPos,40,$FontSize,_('Units'), 'center');
	$LeftOvers = $pdf->addTextWrap(437,$YPos,60,$FontSize,_('Cost'), 'center');
	$LeftOvers = $pdf->addTextWrap(500,$YPos,70,$FontSize,_('Extended Cost'), 'right');
} else {
	$LeftOvers = $pdf->addTextWrap($Xpos,$YPos,320-$Left_Margin,$FontSize,_('Category'), 'center');
	$LeftOvers = $pdf->addTextWrap(360,$YPos,60,$FontSize,_('Quantity'), 'right');
	$LeftOvers = $pdf->addTextWrap(490,$YPos,70,$FontSize,_('Cost'), 'right');
}

$FontSize=8;
$YPos =$YPos - (2*$line_height);

$PageNumber++;

?>