<?php
include $PathPrefix.'modulos/shifts/DAO/caja.inc';
$Title = _('Cotejar cifras');
$titleIcon = $RootPath.'/css/'.$Theme.'/images/transactions.png';
$textoAyudaPagina = "Pantalla para Comparar el efectivo en caja con el efectivo registrado";
$efectivoSys=getEfectivoCaja($_SESSION['UserID']);
$tarjetaSys=getTarjetaCaja($_SESSION['UserID']);
$efectivoIn=intval($_POST['txtEfe']);
$tarjetaIn=intval($_POST['txtTdc']);
$efectivoOk=$efectivoIn==$efectivoSys?'ok':($efectivoIn-$efectivoSys);
$tarjetaOk=$tarjetaIn==$tarjetaSys?'ok':($tarjetaIn-$tarjetaSys);
$efeEstimado=$efectivoSys;
$billetes=getBilletes($efeEstimado);
$monedas=getMonedas($efeEstimado);
function getBilletes(&$monto){
    $billetes = array('1000' => 0, 
                              '500' => 0,
                              '200' => 0,
                              '100' => 0,
                              '50' => 0,
                              '20' => 0);

    return estimarDenominacion($billetes,$monto);
}
function getMonedas(&$monto){
    $monedas = array('20' => 0, 
                              '10' => 0,
                              '5' => 0,
                              '2' => 0,
                              '1' => 0,
                              '.50' => 0,
                              '.20' => 0,
                              '.10' => 0);

    return estimarDenominacion($monedas,$monto);
}
function estimarDenominacion($monedas,&$monto){
    foreach ($monedas as $key => $value) {
        $unidades=($monto - fmod($monto , floatval($key))) / $key;
        $monto-=$key*$unidades;
        $monedas[$key]=$unidades;
		if($monto==0){
			break;
		}
    }
    return $monedas;
}