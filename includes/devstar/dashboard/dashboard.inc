<?PHP
include_once $PathPrefix ."api/api_salesman.php";

function saldosVencidos($salesman){
    if(trim($salesman) != '') {
		$SalesLimit = " AND debtorsmaster.debtorno IN (SELECT DISTINCT debtorno FROM custbranch WHERE salesman = '".$_POST['Salesman']."') ";
	} else {
		$SalesLimit = '';
	}
    $Sql = "SELECT debtorsmaster.debtorno,
    debtorsmaster.name,
    currencies.currency,
    currencies.decimalplaces,
    paymentterms.terms,
    debtorsmaster.creditlimit,
    holdreasons.dissallowinvoices,
    holdreasons.reasondescription,
    SUM(
        debtortrans.ovamount + debtortrans.ovgst + debtortrans.ovfreight + debtortrans.ovdiscount - debtortrans.alloc
    ) AS balance,
    SUM(
        CASE WHEN (paymentterms.daysbeforedue > 0)
        THEN
            CASE WHEN (TO_DAYS(Now()) - TO_DAYS(debtortrans.trandate)) >= paymentterms.daysbeforedue
            THEN debtortrans.ovamount + debtortrans.ovgst + debtortrans.ovfreight + debtortrans.ovdiscount - debtortrans.alloc
            ELSE 0 END
        ELSE
            CASE WHEN TO_DAYS(Now()) - TO_DAYS(ADDDATE(last_day(debtortrans.trandate), paymentterms.dayinfollowingmonth)) >= 0
            THEN debtortrans.ovamount + debtortrans.ovgst + debtortrans.ovfreight + debtortrans.ovdiscount - debtortrans.alloc ELSE 0 END
        END
    ) AS due,
    SUM(
        CASE WHEN (paymentterms.daysbeforedue > 0)
        THEN
            CASE WHEN (TO_DAYS(Now()) - TO_DAYS(debtortrans.trandate)) > paymentterms.daysbeforedue AND TO_DAYS(Now()) - TO_DAYS(debtortrans.trandate) >= (paymentterms.daysbeforedue + " . $_SESSION['PastDueDays1'] . ")
            THEN debtortrans.ovamount + debtortrans.ovgst + debtortrans.ovfreight + debtortrans.ovdiscount - debtortrans.alloc ELSE 0 END
        ELSE
            CASE WHEN TO_DAYS(Now()) - TO_DAYS(ADDDATE(last_day(debtortrans.trandate), paymentterms.dayinfollowingmonth)) >= " . $_SESSION['PastDueDays1'] . "
            THEN debtortrans.ovamount + debtortrans.ovgst + debtortrans.ovfreight + debtortrans.ovdiscount - debtortrans.alloc
            ELSE 0 END
        END
    ) AS overdue1,
    SUM(
        CASE WHEN (paymentterms.daysbeforedue > 0)
        THEN
            CASE WHEN (TO_DAYS(Now()) - TO_DAYS(debtortrans.trandate)) > paymentterms.daysbeforedue AND TO_DAYS(Now()) - TO_DAYS(debtortrans.trandate) >= (paymentterms.daysbeforedue + " . $_SESSION['PastDueDays2'] . ")
            THEN debtortrans.ovamount + debtortrans.ovgst + debtortrans.ovfreight + debtortrans.ovdiscount - debtortrans.alloc ELSE 0 END
        ELSE
            CASE WHEN TO_DAYS(Now()) - TO_DAYS(ADDDATE(last_day(debtortrans.trandate), paymentterms.dayinfollowingmonth)) >= " . $_SESSION['PastDueDays2'] . "
            THEN debtortrans.ovamount + debtortrans.ovgst + debtortrans.ovfreight + debtortrans.ovdiscount - debtortrans.alloc
            ELSE 0 END
        END
    ) AS overdue2
    FROM debtorsmaster,
        paymentterms,
        holdreasons,
        currencies,
        debtortrans
    WHERE debtorsmaster.paymentterms = paymentterms.termsindicator
        AND debtorsmaster.currcode = currencies.currabrev
        AND debtorsmaster.holdreason = holdreasons.reasoncode
        AND debtorsmaster.debtorno = debtortrans.debtorno
        " . $SalesLimit . "
    GROUP BY debtorsmaster.debtorno,
        debtorsmaster.name,
        currencies.currency,
        paymentterms.terms,
        paymentterms.daysbeforedue,
        paymentterms.dayinfollowingmonth,
        debtorsmaster.creditlimit,
        holdreasons.dissallowinvoices,
        holdreasons.reasondescription
    HAVING
        ROUND(ABS(SUM(debtortrans.ovamount + debtortrans.ovgst + debtortrans.ovfreight + debtortrans.ovdiscount - debtortrans.alloc)),currencies.decimalplaces) > 0";
    $CustomerResult = DB_query($Sql, '', '', False, False); /*dont trap errors handled below*/

    if(DB_error_no() != 0) {
        throw new Exception(_('The customer details could not be retrieved by the SQL because') 
        . ' ' . DB_error_msg());

    }
    return $CustomerResult;
}

function getCheckSalespList($filter=[]) {
	if(isset($filter['inicio'])){
		$where=" where date(inicio) ='".$filter['inicio']."'";
	}
    if(isset($filter['fin'])){
        $prefix=$where?' AND':' WHERE';

        if($filter['fin']==''){
            $wherePart=" fin is null ";
        }else{
		    $wherePart=" date(fin) ='".$filter['fin']."'";
        }
        $where=$where.$prefix.$wherePart;
	}    
    if(isset($filter['salesmancode'])){
        $prefix=$where?' AND':' WHERE';
        $where=$where.$prefix." cs.salesmancode ='".$filter['salesmancode']."'";
	}
	$sql = 'SELECT cs.salesmancode,salesmanname, DATE_FORMAT(inicio, \'%T\') inicio,
        DATE_FORMAT(fin, \'%T\') fin FROM checksalesp cs
    LEFT JOIN salesman sm ON sm.salesmancode=cs.salesmancode'.
		$where;
	$result = DB_query($sql);
	return DB_fetch_all($result);
}

function registrarEntradaSalida($salesPerson) {
    if(!$salesPerson)
        throw Exception(_('Salesperson is needed.'));
    $filter=[];
    $filter['fin']='';
    $filter['salesmancode']=$salesPerson;
    $found=getCheckSalespList($filter);
    if(count($found)==0){
        $exist=getSalesmanFilter(["salesmancode"=>$salesPerson,
                        "current"=>1]);
        if($exist){     
            $sql="INSERT INTO checksalesp (salesmancode,inicio)
            VALUES 
            ('".$salesPerson."',NOW())";
        }else{
            throw Exception(_('Salesperson does not exists.'));
        }               
    }else{
        $sql="UPDATE checksalesp 
        SET fin=NOW() 
        WHERE fin is NULL AND salesmancode='".$salesPerson."'";
    }
	return DB_query($sql);
}

function getEscasos($preview=true) {
    $sql="SELECT * from (SELECT stockmaster.stockid id,
            stockmaster.description nombre,
            stockcategory.categorydescription categoria,
            suppliers.suppname proveedor,
            SUM(locstock.quantity) cantidad
        FROM locstock INNER JOIN stockmaster
        ON locstock.stockid=stockmaster.stockid
        INNER JOIN locations
        ON locstock.loccode=locations.loccode
        INNER JOIN stockcategory
        ON stockmaster.categoryid = stockcategory.categoryid
        INNER JOIN purchdata
        ON stockmaster.stockid=purchdata.stockid
        INNER JOIN suppliers
        ON purchdata.supplierno=suppliers.supplierid
        WHERE locstock.quantity <> 0
        AND (stockmaster.mbflag='B' OR stockmaster.mbflag='M') 
        GROUP BY locstock.stockid) as qry
        order by cantidad ASC
        LIMIT 10";
    $ErrMsg = _('The sales data could not be retrieved because') . ' - ' . DB_error_msg();
	$Result = DB_query($sql,$ErrMsg);
    return DB_fetch_all($Result);
}
function getPopulares($preview=true) {
    $sql="SELECT stockmaster.stockid id,
                stockmaster.description nombre,
                stockcategory.categorydescription categoria,
                suppliers.suppname proveedor,
                SUM(CASE WHEN stockmoves.type=10
                        OR stockmoves.type=11 THEN
                        -qty
                        ELSE 0 END) as cantidad
            FROM stockmoves INNER JOIN stockmaster
                ON stockmoves.stockid=stockmaster.stockid
                INNER JOIN stockcategory
                ON stockmaster.categoryid=stockcategory.categoryid
                INNER JOIN purchdata
                ON stockmaster.stockid=purchdata.stockid
                INNER JOIN suppliers
                ON purchdata.supplierno=suppliers.supplierid
            WHERE (stockmoves.type=10 or stockmoves.type=11)
                AND show_on_inv_crds =1
                AND trandate>=date_sub(sysdate(),INTERVAL 100 DAY)
            GROUP BY stockmaster.stockid,
                stockmaster.description,
                stockcategory.categorydescription 
            ORDER BY cantidad DESC 
            LIMIT 10";
    $ErrMsg = _('The sales data could not be retrieved because') . ' - ' . DB_error_msg();
	$Result = DB_query($sql,$ErrMsg);
    return DB_fetch_all($Result);
}