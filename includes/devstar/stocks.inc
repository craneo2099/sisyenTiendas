<?php

use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Component\Cache\Psr16Cache;

include_once($PathPrefix.'includes/SQL_CommonFunctions.inc');
include $PathPrefix.'vendor/autoload.php';

function getMeasureUnits(){
    $sql = "SELECT unitname FROM unitsofmeasure ORDER by unitname";
	$UOMResult = DB_query($sql);
    return DB_fetch_all($UOMResult);

}
function getLocations(){
    $sql = "SELECT locations.loccode FROM locations";
	$loResult = DB_query($sql);
    return DB_fetch_all($loResult);

}
function getCategories(){
    $sql = "SELECT categoryid, categorydescription FROM stockcategory order by categoryid asc";
	$ErrMsg = _('The stock categories could not be retrieved because');
	$DbgMsg = _('The SQL used to retrieve stock categories and failed was');
	$result = DB_query($sql,$ErrMsg,$DbgMsg);
    return DB_fetch_all($result);
}

function saveItemPrice($ItemPrice){
    $Item=$ItemPrice['StockId'];
    $endDate=isset($ItemPrice['endDate'])?FormatDateForSQL($ItemPrice['endDate']):"9999-12-31";
    $sql = "INSERT INTO prices (stockid,
                typeabbrev,
                currabrev,
                startdate,
                enddate,
                price)
            VALUES ('" . $Item . "',
            '" . $ItemPrice['TypeAbbrev'] . "',
            '" . $ItemPrice['CurrAbrev'] . "',
            '" . FormatDateForSQL($ItemPrice['StartDate']) . "',
            '" . $endDate. "',
            '" . filter_number_format($ItemPrice['Price']) . "')";
            $ErrMsg = _('The new price could not be added');
            $result = DB_query($sql,$ErrMsg);

            ReSequenceEffectiveDates ($Item,$ItemPrice['TypeAbbrev'], $ItemPrice['CurrAbrev']) ;
}

function saveStockItems($POST){
    $msgList=array();
    $lastIdx=$POST['lastIdx'];
    $ItemPrice=$POST['PriceDefaults'];
    for ($rowIdx=0; $rowIdx <= $lastIdx; $rowIdx++) { 
        if(!isset($POST['StockID'.$rowIdx]))
            continue;
        $StockID=$POST['StockID'.$rowIdx];

        if (existeStock($StockID)){
            $msjStock=array("text"=>_('The Stock Item code must be unique. Please re-enter a unique Stock Item code.'),
                            "type"=>'danger',
                            "id"=>$StockID);
            $msgList[$rowIdx]=$msjStock;

        } else {
            $existencias=$POST['noStock'.$rowIdx];
            $stock=new \stdClass;
            $stock->StockID= $StockID ;
            $stock->Description=$POST['Description'.$rowIdx] ;
            $stock->LongDescription=$POST['LongDescription'.$rowIdx] ;
            $stock->CategoryID=$POST['CategoryID'.$rowIdx] ;
            $stock->Units=$POST['Units'.$rowIdx]??'each' ;
            $stock->MBFlag=$POST['MBFlag'.$rowIdx] ;
            $stock->EOQ=0;
            $stock->Discontinued=0;
            $stock->Controlled=$POST['Controlled'.$rowIdx]??0 ; 
            $stock->Serialised=$POST['Serialised'.$rowIdx]??0 ; 
            $stock->Perishable=$POST['Perishable'.$rowIdx]??0 ; 
            $stock->Volume=filter_number_format($POST['Volume'.$rowIdx]) ;
            $stock->NetWeight=filter_number_format($POST['NetWeight'.$rowIdx]) ; 
            $stock->BarCode=$POST['BarCode'.$rowIdx] ; 
            $stock->DiscountCategory='';
            $stock->TaxCat=6;
            $stock->DecimalPlaces=$POST['DecimalPlaces'.$rowIdx]??0 ; 
            $stock->materialCost=empty($POST['costo'.$rowIdx])?0.0:$POST['costo'.$rowIdx] ; 
            DB_Txn_Begin();
            $sql = "INSERT INTO stockmaster (stockid,
                        description,
                        longdescription,
                        categoryid,
                        units,
                        mbflag,
                        controlled,
                        serialised,
                        perishable,
                        volume,
                        netweight,
                        barcode,
                        materialcost,
                        decimalplaces,
                        taxcatid)
                    VALUES ('" . $stock->StockID . "',
                        '" . $stock->Description . "',
                        '" . $stock->LongDescription . "',
                        '" . $stock->CategoryID . "',
                        '" . $stock->Units . "',
                        '" . $stock->MBFlag . "',
                        '" . $stock->Controlled . "',
                        '" . $stock->Serialised . "',
                        '" . $stock->Perishable . "',
                        '" . $stock->Volume . "',
                        '" . $stock->NetWeight . "',
                        '" . $stock->BarCode . "',
                        '" . $stock->materialCost . "',
                        '" . $stock->DecimalPlaces . "',
                        '" . $stock->TaxCat . "')";
    
            $ErrMsg =  _('The item could not be added because');
            $DbgMsg = _('The SQL that was used to add the item failed was');
            $result = DB_query($sql, $ErrMsg, $DbgMsg,'',true);
            if (DB_error_no() ==0) {
                $sqlloc="INSERT INTO locstock (loccode,".($existencias?"quantity,":"")."
                    stockid)
                    SELECT locations.loccode,".($existencias?$existencias.",":"")."
                        '" . $StockID . "'
                        FROM locations";					
                $ErrMsg =  _('The locations for the item') . ' ' . $StockID .  ' ' . _('could not be added because');
                $DbgMsg = _('NB Locations records can be added by opening the utility page') . ' <i>Z_MakeStockLocns.php</i> ' . _('The SQL that was used to add the location records that failed was');
                $InsResult = DB_query($sqlloc,$ErrMsg,$DbgMsg,true);
                $msjStock=array("text"=>_('Stock item added'),
                    "type"=>'info',
                    "id"=>$StockID);
                $msgList[$rowIdx]=$msjStock;

                $ItemPrice['Price']=$POST['precio'.$rowIdx];
                $ItemPrice['StockId']=$StockID;
                saveItemPrice($ItemPrice);
            }
            DB_Txn_Commit();
            saveItemRemote($stock);
        }
    }
    return $msgList;
}
function saveItemRemote($stock){
    $post_fields=json_encode($stock);
    $curl = curl_init();
    $end_point='http://devstar-novatech.com/apps/comunes/public/erp/setStock';
    curl_setopt_array($curl, array(
        CURLOPT_URL => $end_point,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT_MS => 300,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => $post_fields,
        CURLOPT_HTTPHEADER => array(
            "Cache-Control: no-cache",
            "Content-Type: application/json"
        ),
    ));
    curl_exec($curl);
    curl_close($curl);

}

function runAction(&$post,&$get){
    $salida=0;
    if(isset($post)&&sizeof($post)>0){
        $dataset=&$post;
    }else if(isset($get)&&sizeof($get)>0){
        $dataset=&$get;
    }else{
        return 0;
    }
    if(existeStock($dataset['StockID'])){
        $salida=2;
    } else
	if (isset($dataset['findBarCode'])){
        askData($dataset);
        $salida=1;
    }
    return $salida;
}
function askData(&$ctx){

    if(isset($ctx['StockID'])){
        try{
            $httpClient = new \GuzzleHttp\Client(['verify' => false]);
            $api        = new \OpenFoodFacts\Api('food', 'mx');
            $product    = $api->getProduct($ctx['StockID']);
        }catch(Exception $e){
            return ;
        }
        $ctx['Description']=$product->product_name;
        $ctx['Description_es_MX_utf8']=$product->product_name_es;
        $ctx['LongDescription']=$product->product_name.' '.$product->generic_name;
        $ctx['LongDescription_es_MX_utf8']= $ctx['LongDescription'];
        $ctx['NetWeight']=$product->product_quantity/1000;
        $ctx['Perishable']=$product->expiration_date?1:0;
        $ctx['BarCode']=$ctx['StockID'];
    }
    return ;

}
function existeStock($StockID){
    $sql = "SELECT COUNT(stockid)
			FROM stockmaster
			WHERE stockid='".$StockID."'";

	$result = DB_query($sql);
    $sale=DB_fetch_array($result);
    return $sale[0]==true;
}

function getStockItemRemote($barcode){
    global $ws_endpoint;
    $curl = curl_init();
    $end_point=$ws_endpoint.'getStock?barCode='.$barcode;
    curl_setopt_array($curl, array(
        CURLOPT_URL => $end_point,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT_MS => 5000,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_POSTFIELDS => $post_fields,
        CURLOPT_HTTPHEADER => array(
            "Cache-Control: no-cache",
            "Content-Type: application/json"
        ),
    ));
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    
    if ($err) {
      throw new \Exception('Error al invocar el WS de Stock.');
    }
    return json_decode($response,true);

}