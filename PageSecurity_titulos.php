<?php


include('includes/session.php');
$Title = _('Page Security Levels');
include('includes/header.php');

echo '<p class="page_title_text"><img src="'.$RootPath.'/css/'.$Theme.'/images/security.png" title="' . _('Page Security Levels') . '" alt="" />' . ' ' . $Title . '</p><br />';

if (isset($_POST['Update']) AND $AlloDemoMode!= true) {
	$showMsg= true;
	foreach ($_POST as $ScriptName => $PageSecurityValue) {
		if ($ScriptName!='Update' and $ScriptName!='FormID') {
			$ScriptName = mb_substr($ScriptName, 0, mb_strlen($ScriptName)-4).'.php';
			$sql="UPDATE scripts SET pagesecurity='". $PageSecurityValue . "' WHERE script='" . $ScriptName . "'";
			$UpdateResult=DB_query($sql,_('Could not update the page security value for the script because'));
			if(!$UpdateResult ) {
				$showMsg=false;
			}
		}
	}
	if($showMsg){
		prnMsg( _('The page security value has been updated'),'success');
	}
}

$sql="SELECT script,
			pagesecurity,
			description
		FROM scripts";

$result=DB_query($sql);

echo '<br /><form method="post" id="PageSecurity" action="' . htmlspecialchars($_SERVER['PHP_SELF'],ENT_QUOTES,'UTF-8') . '">';
echo '<div>';
echo '<input type="hidden" name="FormID" value="' . $_SESSION['FormID'] . '" />';

echo '<table class="selection">';

$TokenSql="SELECT tokenid,
					tokenname
			FROM securitytokens
			ORDER BY tokenname";
$TokenResult=DB_query($TokenSql);

while ($myrow=DB_fetch_array($result)) {
	$BaseName = '/'.basename($myrow['script']);
	if($itemPath=recursive_search_array($BaseName,$MenuItems)){
		$sitepath=explode('|',$itemPath);
		$titulo=_($MenuItems->{$sitepath[0]}->{$sitepath[1]}->Caption[$sitepath[3]]);
		$sitepath[0]=$ModuleList[
			array_search( $sitepath[0] , $ModuleLink )];
		$sitepath[2]=$titulo;
		array_pop($sitepath);
		$siteRuta=implode("/",$sitepath);
		
	}
	echo '<tr>
	<td>'.$sitepath[0].'</td>
	<td>'._($sitepath[1]).'</td>
	<td>'.$sitepath[2].'</td>
	<td>' . $myrow['script'] . '</td>
			<td>';

	while ($myTokenRow=DB_fetch_array($TokenResult)) {
		if ($myTokenRow['tokenid']==$myrow['pagesecurity']) {
			echo  $myTokenRow['tokenname'] ;
		} 
	}
	echo '</td>
		</tr>';
	DB_data_seek($TokenResult, 0);
	$sitepath=array();
}

echo '</table><br />';

echo '<div class="centre">
		<input type="submit" name="Update" value="'._('Update Security Levels').'" />
	</div>
	<br />
    </div>
	</form>';

include('includes/footer.php');
?>