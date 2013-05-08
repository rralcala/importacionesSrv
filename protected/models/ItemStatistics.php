<?php

class ItemStatistics extends CFormModel
{
    private $sales;

    public $item;
    public $period=3; //Custom period defaults to 3 months;
    public $mean;
    public $stdDev;
    public $meanDev;
    public $periodMean;
    public $periodStdDev;
    public $periodMeanDev;
    public $lastSales;
    public $lastStockBreak;
    public $meanWithinSales; // Media de los meses en los que SE vendio.
    public $maxSalesMonth; // Mes en el que se vendio MAS;
    public $trend;
    public $trend2;
    public $trend3;
    public $trendMean; // Media de tendencias.
    public $trendStdDev; // Desviacion Estandar de tendencias.
    public $trendStdDevX3; // 3x desviacion estandar tendencias.
    public $totalPeriod=12;
    public $Stock;

    public function rules()
    {

    }
    /**
     * Returns de Items Statistics
     * 
     * @param integer $id the ID of the item to be calculated
     * @param date $fromDate specifies the period to be calculated
     * @param date $toDate specifies the period to be calculated
     * @return ItemStatistics the instance of the calculated period
     */
    public static function findAll($fromDate, $toDate, $periodFrom, $periodTo, $start, $limit, $page, $filters=array())
    {
        
        //criteria simple.
        $criteria = new CDbCriteria;
        
        foreach($filters as $col => $val){
            $criteria->addInCondition($col, $val);
            $criteria->order = 'Code';
        }
        
        $dataProvider = new CActiveDataProvider('Item', array(
            'criteria' => $criteria, 
            'pagination' => array(
                'pageSize' => $limit,
                'currentPage' => $page
                
            ),
        ));
       
        
        $Items = $dataProvider->getData(true);
        $totalCount = $dataProvider->pagination->getItemCount();
        
        $c = 0;
        $itStat = null;
        
        foreach ($Items as $item)
        {
            $itStat[$c] = new ItemStatistics;
            $itStat[$c]->item= $item;
            ItemStatistics::find($itStat[$c], $fromDate, $toDate, $periodFrom, $periodTo);
            $c++;
        }
        return array("resultSet" => $itStat, "totalCount" => $totalCount);

    }

    public static function findByPk($pk, $fromDate, $toDate, $periodFrom, $periodTo)
    {
        $itStat = new ItemStatistics;
        $itStat->item=Item::model()->findByPk($pk);
        return ItemStatistics::find($itStat, $fromDate, $toDate, $periodFrom, $periodTo);
    }

	public static function getDataAndMean($itemId, $fromDate, $toDate)
	{
	    $sum = 0;
	    $Qtys = array();
	    $mean = 0;
		$x = array();
	    //Calculos de Fechas
	    $fromDateTime = new DateTime($fromDate);
	    $toDateTime = new DateTime($toDate);
	    $diff = $toDateTime->diff($fromDateTime);
	    $months = ($diff->y * 12 ) + $diff->m + 1;
	   // $months = 12; 
		//error_log('Implementar datetime diff en ItemStatistics model');
	    $criteria=new CDbCriteria;
	    $criteria->select="item_id, OperDate, -Qty AS Qty";
	    $criteria->condition="OperDate >= '".$fromDate."' AND OperDate <= '".$toDate."' AND item_id = '".$itemId."'";
	    $criteria->order="OperDate";
		$sales = SalesByDay::model()->findAll($criteria); // Esta llamada viene cargada ! 
	    
	    $totalRows = count($sales);
	    $periodStart = false;
	    if($totalRows > 0)
	    {
	       list($x, $Qtys, $sum) = ItemStatistics::getDataArray($months, $sales, $totalRows, $fromDate, $toDate,true);

	       if($months > 0) 
	            $mean = $sum / $months;
	    }
	    return array($totalRows, $months, $x, $Qtys, $mean);
	}

    public static function find($itStat, $fromDate, $toDate, $periodFrom, $periodTo)
    {
    	$itStat->meanWithinSales = $itStat->Stock = $itStat->item->getStock($toDate);

		list($totalRows, $months, $x, $Qtys, $itStat->mean) = ItemStatistics::getDataAndMean($itStat->item['id'], $fromDate, $toDate);
		
		list($pTotalRows, $pMonths, $pX, $pQtys, $itStat->periodMean) = ItemStatistics::getDataAndMean($itStat->item['id'], $periodFrom, $periodTo);
		$itStat->period = (substr($periodTo, 0,4) - substr($periodFrom, 0,4)) * 12;
		$itStat->period += substr($periodTo, 5,2) - substr($periodFrom, 5,2) + 1; 

		$itStat->totalPeriod = (substr($toDate, 0,4) - substr($fromDate, 0,4)) * 12;
		$itStat->totalPeriod += substr($toDate, 5,2) - substr($fromDate, 5,2) + 1;
		
        if($totalRows > 0)
        {   
        	        	
        	list($itStat->periodStdDev, $itStat->periodMeanDev) = ItemStatistics::getDeviations($itStat->periodMean, $pQtys);         
            list($itStat->stdDev, $itStat->meanDev) = ItemStatistics::getDeviations($itStat->mean, $Qtys);

            $itStat->periodMean +=  $itStat->periodMeanDev;
            $lr = ItemStatistics::linear_regression($x, $Qtys);
            $itStat->trend = $lr['b'] + $lr['m'] * $months;
            $itStat->trend2 = $lr['b'] + $lr['m'] * ($months + 1);
            $itStat->trend3 = $lr['b'] + $lr['m'] * ($months + 2);
            $itStat->trendMean = ($itStat->trend + $itStat->trend2 + $itStat->trend3) / 3;
            $itStat->trendStdDev = ItemStatistics::getLinearDeviation($lr, $Qtys);
            $itStat->trendStdDevX3 = $itStat->trendStdDev *3;
        }
        return $itStat;
    }
    
    public static function getLinearDeviation($line, $Qtys)
    {
        $dev = array();
        $devsq = array();

        for($i = 0; $i < count($Qtys); $i++) { 
            $devsq[] = pow($Qtys[$i] - ($line['b'] + ($line['m'] * $i)), 2);
        }
        if(sizeof($devsq) > 0)
        	$stdDev = sqrt(array_sum($devsq) / sizeof($devsq));
        else
        	$stdDev = -1;
        return $stdDev;
    }
    
    
    /**
    * Retorna la desviacion estandar y la media
    * @return array(DesviacionEstandar, DesviacionMedia)
    */
    public static function getDeviations($mean, $Qtys)
    {
        $dev = array();
        $devsq = array();
        foreach($Qtys as $num) {
            $dev [] = abs($num - $mean);
            $devsq[] = pow($num - $mean, 2);
        }
        if(sizeof($devsq) > 0)
        	$stdDev = sqrt(array_sum($devsq) / sizeof($devsq));
        else
        	$stdDev = 0;
        if(sizeof($dev) > 0)
        	$meanDev = array_sum($dev)  / sizeof($dev);
        else
        	$meanDev = 0;
        return array($stdDev, $meanDev);
    }



	public static function getDataArray($months, $sales, $totalRows, $fromDate, $toDate, $debug)
    {
		$from = explode('-',$fromDate);
		$from[1] = $from[1] * 1; 
		$to = explode('-',$toDate);
		$to[1] = $to[1] * 1;
        $c = 0;
        $sum = 0;
        $x = array();
        $Qtys = array();

		$lastMonth = "";
		foreach($sales as $saleDay) {
			$curMonth = substr($saleDay['OperDate'],0,7);
			if($lastMonth != $curMonth) {
				$lastMonth = $curMonth;
					
				$month = substr($curMonth,5,2) + 1 - 1; // + 1 - 1 == toInt()
				$year =  substr($curMonth,0,4);
				
			}
			if(!isset($salesArray[$year][$month])) {
				$salesArray[$year][$month] = 0;
				$salesArrayCount[$year][$month] = 21;
			}
			
			$salesArray[$year][$month] += $saleDay['Qty'];

			
		}
		if(isset($salesArrayCount[$from[0]][$from[1]]))
		{
			$daysOfMonth = date('d',mktime(23,59,59,$from[1] + 1,0,$from[0]));
			$percentOfMonth = ($daysOfMonth - $from[2] + 1) / $daysOfMonth; 
			$salesArrayCount[$from[0]][$from[1]]  = round($percentOfMonth * 21);
			
		}
		if(isset($salesArrayCount[$to[0]][$to[1]]))
		{
			$daysOfMonth = date('d',mktime(23,59,59,$to[1] + 1,0,$to[0]));
			$percentOfMonth = $to[2] / $daysOfMonth; 
			$salesArrayCount[$to[0]][$to[1]] = round($percentOfMonth * 21);
		}
		
		
		// Ubica las ventas en un vector correspondiente a los meses del periodo, si en un mes dado no hubo
		// ventas, se pone 0.
        for($i = 0; $i < $months; $i++) {
            
            if($c < $totalRows && isset($salesArray[$from[0]][$from[1]])) 
            {
            	// No puede ser dividido por 21, tiene que ser dividido la cantidad de dias del periodo considerado.
                $monthSales = $salesArray[$from[0]][$from[1]] / $salesArrayCount[$from[0]][$from[1]] * 21;
                $sum += $monthSales;
                
                $Qtys [] = $monthSales;
                if($c < ($totalRows - 1))
                    $c++;
            }
            else
            {
                $Qtys [] = 0;
                
            }

            $x [] = $i;
            $from[1]++;

            if($from[1] == 13)
            {
                $from[0]++;
                $from[1] = 1;
            }
        }
        
        return array($x,$Qtys,$sum);
    }

    /**
    * linear regression function
    * @param $x array x-coords
    * @param $y array y-coords
    * @returns array() m=>slope, b=>intercept
    */
    public static function linear_regression($x, $y)
    {
        // calculate number points
        $n = count($x);

        // ensure both arrays of points are the same size
        if ($n != count($y)) {

        trigger_error("linear_regression(): Number of elements in coordinate arrays do not match.", E_USER_ERROR);

        }

        // calculate sums
        $x_sum = array_sum($x);
        $y_sum = array_sum($y);

        $xx_sum = 0;
        $xy_sum = 0;

        for($i = 0; $i < $n; $i++) {

        $xy_sum+=($x[$i]*$y[$i]);
        $xx_sum+=($x[$i]*$x[$i]);

        }

        // calculate slope
        if((($n * $xx_sum) - ($x_sum * $x_sum)) == 0) {
        	$m = 0; 
        }
        else {
        	$m = (($n * $xy_sum) - ($x_sum * $y_sum)) / (($n * $xx_sum) - ($x_sum * $x_sum));
        }
        // calculate intercept
        $b = ($y_sum - ($m * $x_sum)) / $n;

        // return result
        return array("m"=>$m, "b"=>$b);

    }

}




?>
