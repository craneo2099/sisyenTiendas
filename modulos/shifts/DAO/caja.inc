<?php
function getSumaCaja($usuarioId,$paymethod){
    $salida=null;
    $SQLap = "SELECT fecha_hora FROM cash_closings cc 
                where id=(
                    SELECT MAX(id) FROM cash_closings cc where usuario=?
                ) 
                and apertura=1";
    $resultado=DB_query_stmt($SQLap,'','','s',array($usuarioId));
    if(!empty($resultado)){
        $fechaHora= DB_fetch_row($resultado)[0];
        $SQL = "select SUM(amount) from banktrans 
            	    where banktranstype=? and userid=? and transdate BETWEEN ? AND ?";
        $entradasSQL=array($paymethod,
                            $usuarioId,
                            $fechaHora,
                            date('Y-m-d H-i-s'));
        $tiposSQL='isss';
        $salida= DB_fetch_row(DB_query_stmt($SQL,'','',$tiposSQL,$entradasSQL))[0];
    }
    return $salida;
}
function getEfectivoCaja($usuarioId){
    return getSumaCaja($usuarioId,2);
}
function getTarjetaCaja($usuarioId){
    return getSumaCaja($usuarioId,4);
}
function esCajaAbierta($usuarioId){
    
    $SQL = "SELECT apertura FROM cash_closings cc where id=(SELECT MAX(id) FROM cash_closings cc where usuario=?)";
	return DB_fetch_row(DB_query_stmt($SQL,'','','s',array($usuarioId)))[0]==1;
}
