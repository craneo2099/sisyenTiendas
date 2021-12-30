<?php
if (version_compare(PHP_VERSION, '8.0.0') < 0) {
    require "php8compat.inc";
}

function mostrarAyuda($message){
    
		echo '<div class="page_help_text col-12"><b>' . _($message) . '</b></div>';
}
function addScriptList(...$scriptList){
    global $RootPath;
    foreach ($scriptList as $script) {
        if(str_starts_with($script,"http")||str_starts_with($script,"\/"))
            echo "
            <script type=\"text/javascript\" src=\"".$script."\"></script>";
        else {
            echo "
            <script type=\"text/javascript\" src=\"".$RootPath.$script."\"></script>";
        }
    }
}
function addcssList(...$cssList){
    global $RootPath;
    foreach ($cssList as $style) {
        if(str_starts_with($style,"http")||str_starts_with($style,"\/"))
            echo "
            <link rel=\"stylesheet\" type=\"text/css\" href=\"".$style."\">";
        else {
            echo "
            <link rel=\"stylesheet\" type=\"text/css\" href=\"".$RootPath.$style."\">";
        }
    }
}
function contarPrefijos($arreglo,$prefijo){
	$count=0;
    foreach($arreglo as $string) {
        if (str_starts_with($string,$prefijo))
            $count++;
    }
    return $count;
}

function getSalesTypesSelect($TypeAbbrev='' , $atts='', $all='', $deleted = '',$none=''){
	
	$filter='';
	$where='';
	if(!$deleted){
		$filter=' activo=1';
	}
	if($filter){
		$where=' where'. $filter;
	}
	$SQL = "SELECT typeabbrev, sales_type FROM salestypes".$where;
	$result = DB_query($SQL);
	if(DB_num_rows($result)==0){
		return prnMsg(_('No sales types/price lists defined'),'error');
		
	}
    if(str_contains($atts,' name=')){
        echo '<select '.$atts.'>';
    }else{
	    echo '<select name="SalesType" '.$atts.'>';
    }
	if($all){
		echo '<option value="AN">' . _('Any Other') . '</option>';
	}
	if($none){
		echo '<option '.($TypeAbbrev?'':'selected="selected" ').'value="0">' . _('No Price List Selected') . '</option>';
	}
	while ($myrow = DB_fetch_array($result)) {
		echo '<option ';
		if ($myrow['typeabbrev']==$TypeAbbrev) {
			echo 'selected="selected" ';
		}
		echo 'value="' . $myrow['typeabbrev'] . '">' . $myrow['sales_type'] . '</option>';
	}
	echo '</select>';
}
function getCategorySelect($selected='' , $atts='', $all='',$none='',$types=''){
	
	$filter='';
	$where='';
	$qry_or='';
	for ($i=0; $i < strlen($types) ; $i++) { 
		$qry_type=$qry_or." stocktype='".$types[$i]."'";
		$qry_or=' OR';
	}
	$filter.=$qry_type;
	if($filter){
		$where=' where'. $filter;
	}
	$SQL = "SELECT categoryid,
				categorydescription
			FROM stockcategory".$where.
			" ORDER BY categorydescription";
	$result = DB_query($SQL);

    if(str_contains($atts,' name=')){
        echo '<select '.$atts.'>';
    }else{
	    echo '<select name="StockCat" '.$atts.'>';
    }
	if($all){
		echo '<option value="All">' . _('All') . '</option>';
	}
	if($none){
		echo '<option '.($selected?'':'selected="selected" ').'value=" "> </option>';
	}
	while ($myrow = DB_fetch_array($result)) {
		echo '<option ';
		if ($myrow['categoryid']==$selected) {
			echo 'selected="selected" ';
		}
		echo 'value="' . $myrow['categoryid'] . '">' . $myrow['categorydescription'] . '</option>';
	}
	echo '</select>';
}

function countSalesTypes(){
	
	$SQL = "SELECT typeabbrev, sales_type FROM salestypes where activo=1";
	return DB_fetch_row(DB_query($sql))[0];
}
function recursive_search_array($needle, $haystack){
	foreach ( $haystack as $key => $value) {
		if(is_array($value)||$value instanceof stdClass){
			if(str_contains($key,$needle)){
				return "";
			}
			if(($subkey=recursive_search_array($needle,$value))!==false){
				return $key.($subkey?'|'.$subkey:'');
			}
		}else 
		if(str_contains($value,$needle)){
			return $key;
		}
	}
	return false;
}
function pntMigajas($migajas,$titulo){
	foreach ($migajas as $key => $value) {
		if($key===0){
			continue;
		}
		$llave=_($key);
		if($titulo === $llave) {
			break;
		}else{
			echo '<a href="'. $value . '">'.$llave.'</a>/';
		}
	} 
	
	echo $titulo;
}