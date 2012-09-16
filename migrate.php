<?php
/* configuration */
$myOrigin = new mysqli("192.168.37.1", "root", "rootpasswd", "oodev");
//$myOrigin = new mysqli("192.168.37.1", "root", "Sw4", "oodev");
$myDestination = new mysqli("192.168.37.1", "root", "rootpasswd", "Importaciones");
$maxInsert = 1000;

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
$myDestination->query("TRUNCATE TABLE SalesByDay");
$myDestination->query("TRUNCATE TABLE Transaction");
$myDestination->query('DELETE FROM Item');

$i=0;

$result = $myOrigin->query("SELECT  i.Code as Code , SUM(Qty - IFNULL(ReceivedQty,0)) as Pending
FROM PurchaseOrder AS po
JOIN PurchaseOrderItemRow AS p ON ( po.internalId = p.masterId )
JOIN Item AS i ON ( p.ArtCode = i.Code )
WHERE i.ItemGroup = 'MERC'
AND (Qty <> ReceivedQty OR ReceivedQty IS NULL) AND FullReceived = 0
GROUP BY i.Code");

while ($row = $result->fetch_row()) {
    $incoming[$row[0]] = $row[1];
}

if ($result = $myOrigin->query("SELECT internalId, Code, Name, Brand, Labels, Unit2, Unit FROM Item WHERE ItemGroup = 'MERC' ORDER BY internalId ")) {
	printf("Items Obtained. %d\n", $result->num_rows);
	while ($row = $result->fetch_row()) {

		$labels = explode(',',$row[4]);
		if(!isset($labels[3]))
		{
			$labels[3]='';
			if(!isset($labels[2]))
			{
				$labels[2]='';
				if(!isset($labels[1]))
				{
					$labels[1]='';
					if(!isset($labels[0]))
						$labels[0]='';
				}
			}
		}
		if(isset($incoming[$row[1]]))
			$incomingRow = $incoming[$row[1]];
		else
			$incomingRow = 0;
		$sql[] = '('.$row[0].',"'.$myOrigin->real_escape_string($row[1]).'","'.$myOrigin->real_escape_string($row[2]).'","'.$row[3].'","'.$row[5].'","'.$row[6].'","'.$labels[0].'","'.$labels[2].'","'.$labels[1].'","'.$labels[3].'", "'.$incomingRow.'","2","USD")';
			
		if($i == $maxInsert)
		{
			$myDestination->query('INSERT INTO Item VALUES '.implode(',', $sql));
			$i = 0;
			unset($sql);
			if($myDestination->affected_rows == -1)
				printf("Error: %s\n", $myDestination->error);
			
		}
		$i++;
        }
	$myDestination->query('INSERT INTO Item VALUES '.implode(',', $sql));
	$result->close();
}
else
	printf("Error: %s\n", $myOrigin->error);

printf("Items Complete\n");
$operation[] ="SELECT '1' as Oper,It.internalId, -IIr.Qty Qty, IIr.Unit, IIr.RowTotal, I.TransDate, I.InvoiceDate as OperDate, I.StockDepo, I.Currency, I.CurrencyRate, I.UpdStockFlag, 1 As Enabled FROM Item It JOIN InvoiceItemRow IIr ON (It.Code = IIr.ArtCode)  INNER JOIN Invoice I ON IIr.masterId = I.internalId WHERE I.Invalid = '0' AND I.Status = '1' AND IIr.ArtCode  <> 'MIGRACION'  AND  It.ItemGroup = 'MERC'";
$operation[] ="SELECT '2' as Oper, It.internalId, GRIr.Qty,  GRIr.Unit,GRIr.RowTotal, GR.TransDate, GR.TransDate as OperDate, GR.StockDepo, GR.Currency,GR.CurrencyRate, 1, 1 As Enabled FROM Item It Join GoodsReceiptItemRow GRIr ON GRIr.ArtCode = It.Code INNER JOIN GoodsReceipt GR ON GRIr.masterId = GR.internalId WHERE (GR.Invalid = '0' OR TransDate = '2010-12-31') AND GR.Status = '1' AND  It.ItemGroup = 'MERC' AND GRIr.ArtCode <> 'MIGRACION'";
//$operation[] ="SELECT '2' as Oper, It.internalId, GRIr.Qty,  GRIr.Unit,GRIr.RowTotal, GR.TransDate, GR.TransDate as OperDate, GR.StockDepo, GR.Currency,GR.CurrencyRate, 1, 1 As Enabled FROM Item It Join GoodsReceiptItemRow GRIr ON GRIr.ArtCode = It.Code INNER JOIN GoodsReceipt GR ON GRIr.masterId = GR.internalId WHERE  GR.Status = '1' AND  It.ItemGroup = 'MERC' AND GRIr.ArtCode <> 'MIGRACION'"; // Acordate na de mirar GR.Invalid = '0' AND
$operation[] ="SELECT '3' AS Oper, It.internalId, -RSIr.Qty Qty, RSIr.Unit, RSIr.RowTotal, RS.TransDate, RS.TransDate as OperDate, RS.StockDepo, RS.Currency, RS.CurrencyRate, 1 , 1 As Enabled FROM  Item It Join ReturnSupplierItemRow RSIr ON RSIr.ArtCode = It.Code INNER JOIN ReturnSupplier RS ON RSIr.masterId = RS.internalId WHERE RS.Invalid = '0' AND RS.Status = '1' AND It.ItemGroup = 'MERC'AND RSIr.ArtCode <> 'MIGRACION'";
$operation[] ="SELECT '4' as Oper, It.internalId, -STIr.Qty Qty, STIr.Unit, STIr.TotalCost RowTotal, STI.TransDate, STI.TransDate as OperDate, STI.StockDepo, Currency, CurrencyRate, 1, 1 AS Enabled FROM Item It JOIN StockTransformationItemInRow STIr  ON STIr.ArtCode = It.Code INNER JOIN StockTransformation STI ON STIr.masterId = STI.internalId WHERE STI.Invalid = '0' AND STI.Status = '1' AND  It.ItemGroup = 'MERC' AND STIr.ArtCode <> 'MIGRACION'";
$operation[] ="SELECT '5' as Oper, It.internalId, STIr.Qty Qty, STIr.Unit, STIr.TotalCost RowTotal, STI.TransDate, STI.TransDate as OperDate, STI.StockDepo, Currency, CurrencyRate, 1, 1 AS Enabled FROM Item It JOIN StockTransformationItemOutRow STIr  ON STIr.ArtCode = It.Code INNER JOIN StockTransformation STI ON STIr.masterId = STI.internalId WHERE STI.Invalid = '0' AND STI.Status = '1' AND  It.ItemGroup = 'MERC' AND STIr.ArtCode <> 'MIGRACION'";
$operation[] ="SELECT '6' as Oper, I.internalId, -SMr.Qty Qty, SMr.Unit ,  IFNULL(SMr.Cost,0) RowTotal,  SMF.TransDate,  SMF.TransDate as OperDate, SMF.FrStockDepo StockDepo,  Currency, CurrencyRate, 1, 1 AS Enabled FROM StockMovementRow SMr INNER JOIN StockMovement SMF ON SMr.masterId = SMF.internalId INNER JOIN Item I ON I.Code = SMr.ArtCode WHERE SMF.Invalid = '0' AND SMF.Status = '1' AND I.ItemGroup = 'MERC'AND SMr.ArtCode <> 'MIGRACION'";
$operation[] ="SELECT '7' as Oper, I.internalId, SMr.Qty Qty, SMr.Unit , IFNULL(SMr.Cost,0) RowTotal, SMF.TransDate,  SMF.TransDate as OperDate,  SMF.ToStockDepo StockDepo,  Currency, CurrencyRate, 1, 1 AS Enabled FROM StockMovementRow SMr INNER JOIN StockMovement SMF ON SMr.masterId = SMF.internalId INNER JOIN Item I ON I.Code = SMr.ArtCode WHERE SMF.Invalid = '0' AND SMF.Status = '1' AND I.ItemGroup = 'MERC'AND SMr.ArtCode <> 'MIGRACION'";
$operation[] ="SELECT '8' as Oper, I.internalId, -SDr.Qty Qty,  SDr.Unit, SDr.Cost Price , SD.TransDate, SD.TransDate as OperDate, SD.StockDepo, Currency, CurrencyRate, 1, 1 AS Enabled FROM Item I JOIN StockDepreciationRow SDr ON (I.Code = SDr.ArtCode) INNER JOIN StockDepreciation SD ON SDr.masterId = SD.internalId WHERE SD.Invalid = '0' AND SD.Status = '1' AND I.ItemGroup = 'MERC'AND SDr.ArtCode <> 'MIGRACION'";
$operation[] ="SELECT '9' as Oper, TI.internalId, -Dr.Qty*Drow.Qty Qty, Dr.Unit, Dr.Cost Price, D.TransDate, D.DeliveryDate as OperDate, D.StockDepo, Currency, CurrencyRate, 1, 1 AS Enabled FROM TransactionRecipe Dr INNER JOIN Delivery D ON Dr.OriginNr = D.SerNr INNER JOIN DeliveryRow Drow ON Drow.masterId = D.internalId AND Drow.Recipe = Dr.RecipeCode INNER JOIN Item TI ON TI.Code = Dr.ArtCode WHERE D.Invalid = '0' AND (D.Status = '2' OR D.Status = '1') AND TI.Code  <> 'MIGRACION'  AND  TI.ItemGroup = 'MERC'";
$operation[] ="SELECT '10' as Oper, TI.internalId, -Ir.Qty*Irow.Qty Qty, Ir.Unit, Ir.Cost Price, Inv.TransDate, Inv.InvoiceDate as OperDate, Inv.StockDepo, Currency, CurrencyRate, 1, 1 AS Enabled FROM TransactionRecipe Ir INNER JOIN Invoice Inv ON Ir.OriginNr = Inv.SerNr INNER JOIN InvoiceItemRow Irow ON Irow.masterId = Inv.internalId AND Irow.Recipe = Ir.RecipeCode INNER JOIN Item TI ON TI.Code = Ir.ArtCode WHERE Inv.Invalid = '0' AND Inv.Status = '1' AND TI.Code  <> 'MIGRACION' AND  TI.ItemGroup = 'MERC'";
$operation[] ="SELECT '11' as Oper, TI.internalId,  -SDr.Qty*SDrow.Qty Qty, SDr.Unit, SDr.Cost Price, SD.TransDate, SD.TransDate as OperDate, SD.StockDepo, Currency, CurrencyRate, 1, 1 AS Enabled FROM TransactionRecipe SDr INNER JOIN StockDepreciation SD ON SDr.OriginNr = SD.SerNr INNER JOIN StockDepreciationRow SDrow ON SDrow.masterId = SD.internalId AND SDrow.Recipe = SDr.RecipeCode INNER JOIN Item TI ON TI.Code = SDr.ArtCode WHERE SD.Invalid = '0' AND SD.Status = '1' AND TI.Code  <> 'MIGRACION' AND  TI.ItemGroup = 'MERC'";
$operation[] ="SELECT '12' as Oper, TI.internalId,  RCr.Qty*RCrow.Qty Qty,  RCr.Unit, RCr.Cost Price, RC.TransDate, RC.TransDate as OperDate, RC.StockDepo, Currency, CurrencyRate, 1, 1 AS Enabled FROM TransactionRecipe RCr  INNER JOIN ReturnCustomer RC ON RCr.OriginNr = RC.SerNr INNER JOIN ReturnCustomerItemRow RCrow ON RCrow.masterId = RC.internalId AND RCrow.Recipe = RCr.RecipeCode  INNER JOIN Item TI ON TI.Code = RCr.ArtCode WHERE RC.Invalid = '0' AND RC.Status = '1' AND TI.Code  <> 'MIGRACION' AND ActStock = '1' AND  TI.ItemGroup = 'MERC'";
$operation[] ="SELECT '13' as Oper, TI.internalId, -Dr.Qty Qty,  Dr.Unit, Dr.Cost Price, D.TransDate, D.DeliveryDate as OperDate, D.StockDepo, Currency, CurrencyRate, 1, 1 AS Enabled  FROM Item TI JOIN DeliveryRow Dr ON (TI.Code = Dr.ArtCode) INNER JOIN Delivery D ON Dr.masterId = D.internalId  WHERE D.Invalid = '0' AND (D.Status = '2' )  AND TI.ItemGroup = 'MERC'AND Dr.ArtCode <> 'MIGRACION'"; // OR D.Status = '1' Picking
$operation[] ="SELECT '14' as Oper, TI.internalId, RCIr.Qty,  RCIr.Unit, RCIr.Price, RC.TransDate, RC.TransDate as OperDate, RC.StockDepo, Currency, CurrencyRate, 1, 1 AS Enabled  FROM Item TI JOIN ReturnCustomerItemRow RCIr ON (TI.Code = RCIr.ArtCode) INNER JOIN ReturnCustomer RC ON RCIr.masterId = RC.internalId  WHERE RC.Invalid = '0' AND RC.Status = '1'  AND TI.ItemGroup = 'MERC' AND TI.Code <> 'MIGRACION' AND ActStock = '1'";
$i = 1;
foreach($operation as $oper)
{
	printf("StartingOper".$i." \n");
	if(!insertOperation($myOrigin, $myDestination, $oper, $maxInsert))
	{
		printf("Failed\n");
		exit(0);
	}
	$i++;
}

$myDestination->query("INSERT INTO SalesByDay select `Transaction`.`item_id` AS `item_id`,`Transaction`.`OperDate` AS `OperDate`,`Transaction`.`Unit` AS `Unit`,sum(`Transaction`.`Qty`) AS `Qty` from `Transaction` where ((`Transaction`.`Enable` = '1') and (`Transaction`.`Oper` = '13')) group by `Transaction`.`item_id`,`Transaction`.`OperDate`,`Transaction`.`Unit`");
printf('SalesByDay='.$myDestination->affected_rows."\n");
$myDestination->query("INSERT INTO SalesByDay select `Transaction`.`item_id` AS `item_id`,`Transaction`.`OperDate` AS `OperDate`,`Transaction`.`Unit` AS `Unit`,sum(`Transaction`.`Qty`) AS `Qty` from `Transaction` where ((`Transaction`.`Enable` = '1') and (`Transaction`.`Oper` = '14')) group by `Transaction`.`item_id`,`Transaction`.`OperDate`,`Transaction`.`Unit`");
printf('ReturnsSalesByDay='.$myDestination->affected_rows."\n");

printf("Bye\n");
$myOrigin->close();
$myDestination->close();

function insertOperation($origin, $destination, $query, $maxInsert)
{
	$i =0;
	if ($result = $origin->query($query)) {
		printf("Sales Obtained. %d\n", $result->num_rows);
		while ($row = $result->fetch_row()) {
			$sql[] = '( NULL, "'.$row[0].'","'.$row[1].'","'.$row[2].'","'.$row[3].'","'.$row[4].'","'.$row[5].'","'.$row[6].'","'.$row[7].'","'.$row[8].'","'.$row[9].'","'.$row[10].'","'.$row[11].'")';
			$i++;
			if($i == $maxInsert)
			{
				$destination->query('INSERT INTO Transaction VALUES '.implode(',', $sql));
				$i = 0;
				unset($sql);
				if($destination->affected_rows == -1)
					printf("Error: %s\n", $destination->error);
				//printf("%d\n", $destination->affected_rows);
			}
			
		}
		if(isset($sql))
		{
			$destination->query('INSERT INTO Transaction VALUES '.implode(',', $sql));
			//printf("%d\n", $destination->affected_rows);
		}
		$result->close();
		$retVal =true;
	}
	else{
		
		printf("Error: %s\n", $myOrigin->error);
		$retVal = false;
	}
	return $retVal;
}

?>
