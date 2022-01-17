<?php

if ($PageNumber>1){
	$pdf->newPage();
}

$YPos = $Page_Height - $Top_Margin;

$pdf->addJpegFromFile($_SESSION['LogoFile'],$Left_Margin,$YPos-60,0,60);



$XPos = $Left_Margin+10;
$pdf->addText(40, $YPos-62,13, $_SESSION['CompanyRecord']['coyname']);
$titlepdf=$TransType . ' ' ._('dated from') . ' ' . $_POST['FromDate'] . ' ' . _('to') . ' ' . $_POST['ToDate'];
$titleW=300;
$pdf->MultiCell($titleW,0,$titlepdf,0, 'L', false, 0, 
	$Page_Width-$Right_Margin-$titleW, $Page_Height-($YPos-15));

$FontSize=10;
$pdf->addTextWrap($Page_Width-$Right_Margin-140, $YPos-$FontSize, 140, $FontSize,
_('Page'). ' ' . $PageNumber, 'right');// Page number.

/*Now print out the company name and address */
$XPos = $Left_Margin;
$YPos -=107;

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
$pdf->MultiCell(160,0,_('Stock Item'),0, 'C', false, 0, 
	$Left_Margin+60,$Page_Height-$YPos-$FontSize );
$LeftOvers = $pdf->addTextWrap($Left_Margin+222,$YPos,70,$FontSize,_('Trans. No'), 'center');
$pdf->MultiCell(50,0,_('Trans Date'),0, 'C', false, 0, 
	$Left_Margin+292,$Page_Height-$YPos-$FontSize );
$LeftOvers = $pdf->addTextWrap($Left_Margin+342,$YPos,60,$FontSize,_('Quantity'), 'center');
$LeftOvers = $pdf->addTextWrap($Left_Margin+402,$YPos,60,$FontSize,_('Location'), 'center');
$LeftOvers = $pdf->addTextWrap($Left_Margin+472,$YPos,60,$FontSize,_('Reference'), 'center');
$YPos-=$line_height;

/*draw a line */
$pdf->line($XPos, $YPos,$Page_Width-$Right_Margin, $YPos);

$YPos -= ($line_height);
?>