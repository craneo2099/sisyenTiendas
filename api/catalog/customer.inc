<?php
function getCustomerName($id){
    
	$values[0]=$id??'';
    return htmlspecialchars(
                DB_fetch_array(
                    DB_query_stmt(
                        "SELECT name FROM debtorsmaster 
                            WHERE debtorno=?"
                        ,'','', 's',$values))['name']
            , ENT_QUOTES, 'UTF-8');
}