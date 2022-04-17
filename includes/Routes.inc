<?php
function getRedirection($path,$action){
    global $RootPath;
    $Routes=array(
        "/Shifts/CorteCaja"=>array(
            "Apertura"=>"/Shifts/AperturaCaja",
            "Cierre"=>"/Shifts/CierreCaja",
            "Cotejar"=>"/Shifts/CotejarCierre",
            "Cerrar"=>"/Shifts/AplicarCierre",
            "Procesar"=>"/Shifts/ProcesarCierre",
            "Abrir"=>"/Shifts/AbrirCaja"
        )
    );
    $actionPath=str_replace($RootPath, "", $path);
    
    if(isset($action)){
        $route=$Routes[$actionPath][$action];
        if($route){
            return explode("/",$route,3);
        }
    }
    return explode("/",$actionPath,3);
}