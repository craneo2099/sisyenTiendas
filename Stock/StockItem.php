<?php 
header('Content-Type: application/json');

include('../includes/session.php');
include('../includes/devstar/stocks.inc');

if(!isset($_GET['barCode'])){
   $Response['message']='Se requiere un codigo de barras';
   $Response['response']=false;
}else
if(existeStock($_GET['barCode'])){
    
   $Response['message']='el articulo ya existe';
   $Response['response']='EXISTE';
}else{
   try {
      $Response=getStockItemRemote($_GET['barCode']);
      $Response['response']=true;
   } catch (\Exception $e) {
      $Response['message']='No se ha encontrado el articulo';
      $Response['response']=true;
      $Result['StockID']=$_GET['barCode'];
      $Result['Description']=null;
      $Result['LongDescription']=null;
      $Result['CategoryID']=0;
      $Result['Units']='c/u';
      $Result['MBFlag']='M';
      $Result['EOQ']=null;
      $Result['Discontinued']='0';
      $Result['Controlled']=null;
      $Result['Serialised']=null;
      $Result['Perishable']=null;
      $Result['Volume']=null;
      $Result['NetWeight']=null;
      $Result['BarCode']=$_GET['barCode'];
      $Result['DiscountCategory']='';
      $Result['TaxCat']='6';
      $Result['DecimalPlaces']='0';
      $Response['result']=$Result;

   }
   
}
echo json_encode($Response);
?>