<?PHP
function setStandardCost($StockID,$NewCost){
    $Result = DB_Txn_Begin();
    $sql = "SELECT materialcost,
        sum(quantity) as totalqoh
    FROM stockmaster INNER JOIN locstock
    ON stockmaster.stockid=locstock.stockid
    WHERE stockmaster.stockid='".$StockID."'
    GROUP BY description,
        units,
        lastcost,
        actualcost,
        materialcost,
        labourcost,
        overheadcost,
        mbflag";
    $ErrMsg = _('The entered item code does not exist');
    $OldResult = DB_query($sql,$ErrMsg);
    if(DB_num_rows($OldResult) == 0){
        $sqlLoc = "INSERT INTO locstock (loccode,
                stockid)
        SELECT locations.loccode,
        '" . $StockID . "'
        FROM locations";

        $ErrMsg =  _('The locations for the item') . ' ' . $StockID .  ' ' . _('could not be added because');
        $DbgMsg = _('NB Locations records can be added by opening the utility page') . ' <i>Z_MakeStockLocns.php</i> ' . _('The SQL that was used to add the location records that failed was');
        DB_query($sqlLoc,$ErrMsg,$DbgMsg,true);
        $OldResult = DB_query($sql,$ErrMsg);
    }
    $OldRow = DB_fetch_array($OldResult);
    $CALM = $OldRow['totalqoh'];
    $OldCost = $OldRow['materialcost'];
    ItemCostUpdateGL($StockID, $NewCost, $OldCost, $CALM);

    $SQL = "UPDATE stockmaster SET lastcost=materialcost,
                materialcost='" . $NewCost . "',
                lastcostupdate ='" . Date('Y-m-d')."'
                WHERE stockid='" . $StockID . "'";

    $ErrMsg = _('The cost details for the stock item could not be updated because');
    $DbgMsg = _('The SQL that failed was');
    $Result = DB_query($SQL,$ErrMsg,$DbgMsg,true);

    $Result = DB_Txn_Commit();
    UpdateCost($StockID); //Update any affected BOMs
    return $Result;
}