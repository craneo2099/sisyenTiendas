<?php

include ('includes/session.php');
$Title = _('Make Inventory Quantities CSV');
include ('includes/header.php');

echo '<p class="page_title_text"><img src="'.$RootPath.'/css/'.$Theme.'/images/inventory.png" title="' . _('Inventory') .'" alt="" /><b>' . $Title. '</b></p>';

function stripcomma($str) { //because we're using comma as a delimiter
	return str_replace(',', '', $str);
}

echo '<div class="centre">' . _('Making a comma separated values file of the current stock quantities') . '</div>';

$ErrMsg = _('The SQL to get the stock quantities failed with the message');

$sql = "SELECT locstock.stockid,stma.description,stca.categorydescription,
			CASE WHEN stma.netweight>0 THEN stma.netweight ELSE stma.volume END,
			stma.units,stma.perishable , SUM(quantity)
			FROM locstock
			INNER JOIN locationusers ON locationusers.loccode=locstock.loccode AND locationusers.userid='" .  $_SESSION['UserID'] . "' AND locationusers.canview=1
			LEFT JOIN stockmaster stma ON locstock.stockid=stma.stockid
			LEFT JOIN stockcategory stca ON stma.categoryid=stca.categoryid
			GROUP BY stockid HAVING SUM(quantity)<>0";
$result = DB_query($sql, $ErrMsg);

if (!file_exists($_SESSION['reports_dir'])){
	$Result = mkdir('./' . $_SESSION['reports_dir']);
}

$filename = $_SESSION['reports_dir'] . '/informeExistenciaDeInventario.csv';

$fp = fopen($filename,'w');

if ($fp==FALSE){

	prnMsg(_('Could not open or create the file under') . ' ' . $_SESSION['reports_dir'] . '/informeExistenciaDeInventario.csv','error');
	include('includes/footer.php');
	exit;
}

While ($myrow = DB_fetch_row($result)){
	$line='';
	$first=true;
	foreach ($myrow as $value) {
		if(!$first){
			$line=$line.',';
		}else{
			$fline=_('code').','._('Description').','._('category').','
			._('Net').','._('Units').','._('Perishable').','._('Stock');
			fputs($fp,"\xEF\xBB\xBF" . $fline . "\n");
		}
		$first=false;
		$line = $line.stripcomma($value);
	}
	fputs($fp,"\xEF\xBB\xBF" . $line . "\n");
}

fclose($fp);

echo '<br /><div class="centre"><a href="' . $RootPath . '/' . $_SESSION['reports_dir'] . '/informeExistenciaDeInventario.csv ">' . _('click here') . '</a> ' . _('to view the file') . '</div>';

include('includes/footer.php');

?>
