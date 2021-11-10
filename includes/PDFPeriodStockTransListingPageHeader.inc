<?php

if ($PageNumber>1){
	$pdf->newPage();
}

$YPos = $Page_Height - $Top_Margin - 50;

$pdf->addJpegFromFile($_SESSION['LogoFile'],$Left_Margin,$YPos,0,50);

$FontSize=15;


$XPos = $Page_Width/2;
$YPos += 30;
$pdf->addText($XPos, $YPos,$FontSize, $_SESSION['CompanyRecord']['coyname']);
$FontSize=12;
$YPos -=30;
$pdf->addText($XPos, $YPos,$FontSize, $TransType . ' ' ._('dated from') . ' ' . $_POST['FromDate'] . ' ' . _('to') . ' ' . $_POST['ToDate']);


$XPos = $Page_Width-$Right_Margin-50;
$YPos -=30;
$pdf->addText($XPos, $YPos+10,$FontSize, _('Page') . ': ' . $PageNumber);

/*Now print out the company name and address */
$XPos = $Left_Margin;
$YPos -= $line_height;

/*draw a square grid for entering line items */
$pdf->line($XPos, $YPos,$Page_Width-$Right_Margin, $YPos);
$pdf->line($Page_Width-$Right_Margin, $YPos,$Page_Width-$Right_Margin, $Bottom_Margin);
$pdf->line($Page_Width-$Right_Margin, $Bottom_Margin,$XPos, $Bottom_Margin);
$pdf->line($XPos, $Bottom_Margin,$XPos, $YPos);

$pdf->line($Left_Margin+60, $YPos,$Left_Margin+60, $Bottom_Margin);
$pdf->line($Left_Margin+220, $YPos,$Left_Margin+220, $Bottom_Margin);
$pdf->line($Left_Margin+290, $YPos,$Left_Margin+290, $Bottom_Margin);
$pdf->line($Left_Margin+340, $YPos,$Left_Margin+340, $Bottom_Margin);
$pdf->line($Left_Margin+404, $YPos,$Left_Margin+404, $Bottom_Margin);
$pdf->line($Left_Margin+464, $YPos,$Left_Margin+464, $Bottom_Margin);

$YPos -= $line_height;
/*Set up headings */
$FontSize=8;
$LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos,60,$FontSize,_('Id'), 'left');
$pdf->MultiCell(65,0,_('Stock Item'),0, 'L', false, 0, 
	$Left_Margin+60,$Page_Height-$YPos-$FontSize );
$LeftOvers = $pdf->addText($FormDesign->Headings->Column2->x,$Page_Height - $FormDesign->Headings->Column2->y, $FormDesign->Headings->Column2->FontSize,  _('Description'));
$LeftOvers = $pdf->addTextWrap($Left_Margin+222,$YPos,70,$FontSize,_('Reference'), 'left');
$LeftOvers = $pdf->addTextWrap($Left_Margin,$YPos,60,$FontSize,_('Id'), 'left');
$pdf->MultiCell(50,0,_('Trans Date'),0, 'L', false, 0, 
	$Left_Margin+292,$Page_Height-$YPos-$FontSize );
$LeftOvers = $pdf->addTextWrap($Left_Margin+342,$YPos,60,$FontSize,_('Quantity'), 'right');
$LeftOvers = $pdf->addTextWrap($Left_Margin+402,$YPos,60,$FontSize,_('Location'), 'right');
$LeftOvers = $pdf->addTextWrap($Left_Margin+462,$YPos,60,$FontSize,_('Reference'), 'right');
$YPos-=$line_height;

/*draw a line */
$pdf->line($XPos, $YPos,$Page_Width-$Right_Margin, $YPos);

$YPos -= ($line_height);
?>