<?php

include $PathPrefix.'modulos/shifts/DAO/caja.inc';
setEstadoCaja($_SESSION['UserID'],1);
$viewModule=null;
$viewName="CounterSales.php";