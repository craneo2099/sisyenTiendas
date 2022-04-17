<?php
include $PathPrefix.'modulos/shifts/DAO/caja.inc';
$Title = _('Corte de Caja');
$titleIcon = $RootPath.'/css/'.$Theme.'/images/transactions.png';
$textoAyudaPagina = "Pantalla para realizar la apertura y cierre de caja";
$Abierto=esCajaAbierta($_SESSION['UserID']);
