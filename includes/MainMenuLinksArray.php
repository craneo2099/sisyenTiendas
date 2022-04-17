<?php
/* webERP menus with Captions and URLs. */

$ModuleLink = array('Sales', 'AR', 'PO', 'AP', 'stock', 'manuf', 'GL', 'FA', 'PC', 'system', 'Utilities','Shifts');
$ReportList = array('Sales' => 'ord', 'AR' => 'ar', 'PO' => 'prch', 'AP' => 'ap', 'stock' => 'inv', 'manuf' => 'man', 'GL' => 'gl', 'FA' => 'fa', 'PC' => 'pc', 'system' => 'sys', 'Utilities' => 'utils','Shifts'=>'sh');

/*The headings showing on the tabs across the main index used also in WWW_Users for defining what should be visible to the user */
$ModuleList = array(_('Sales'), _('Receivables'), _('Purchases'), _('Payables'), _('Inventory'), _('Manufacturing'), _('General Ledger'), _('Asset Manager'), _('Petty Cash'), _('Setup'), _('Utilities'),_('Shifts'));

$MenuItems=json_decode(file_get_contents("json/menu.json"));

//el menu condicional no se ha implementado
if ($_SESSION['InvoicePortraitFormat'] == 0) {
	$PrintInvoicesOrCreditNotesScript = '/PrintCustTrans.php';
} else {
	$PrintInvoicesOrCreditNotesScript = '/PrintCustTransPortrait.php';
}

?>
