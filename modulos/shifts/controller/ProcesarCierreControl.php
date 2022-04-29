<?php

include $PathPrefix.'modulos/shifts/DAO/caja.inc';
setEstadoCaja($_SESSION['UserID'],0);

include ('modulos/Shifts/controller/CorteCajaControl.php');