<?php

/**
 * This is the model class for table "RequestDetail".
 *
 * The followings are the available columns in table 'RequestDetail':
 * @property string $id
 * @property string $request_id
 * @property integer $item_id
 * @property string $StockTime
 * @property string $ShipTime
 * @property string $ManualQty
 *
 * The followings are the available model relations:
 * @property Item $item
 */
class RequestDetail extends CFormModel
{
  private $sales;
  public $id;
  public $request_id;
  public $item_id; //Custom period defaults to 3 months;
  public $itemName;
    public $Line;
    public $Code;
    public $StockTime;
    public $ShipTime;
    public $ManualQty;
    public $estimation1;
    public $weight1;
    public $estimation2;
    public $weight2;
    public $estimation3;
    public $weight3;
    public $estimatedSales;
    public $currentStock; // Media de los meses en los que SE vendio.
    public $pendingStock; // Mes en el que se vendio MAS;
    public $estimatedStock;
    public $price;
    public $suggestedQty;
    public $stockBreaksCount;
    public $orderTotal; // Media de tendencias.
    public $desiredStockTime;
    public $desiredStock;
    
    
	/**
	 * Returns the static model of the specified AR class.
	 * @return RequestDetail the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'RequestDetail';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
	/*	return array(
			array('request_id, item_id, StockTime, ShipTime, ManualQty', 'required'),
			array('item_id', 'numerical', 'integerOnly'=>true),
			array('request_id', 'length', 'max'=>20),
			array('StockTime, ShipTime, ManualQty', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, request_id, item_id, StockTime, ShipTime, ManualQty', 'safe', 'on'=>'search'),
		);*/
	}

	/**
	 * @return array relational rules.
	 */
/*	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'item' => array(self::BELONGS_TO, 'Item', 'item_id'),
		);
	}*/
	
	/*public function behaviors() {
	   return array(
			'EJsonBehavior' => array(
				'class'=>'application.behaviors.EJsonBehavior'
			),
		);
	}*/

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	/*public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'request_id' => 'Request',
			'item_id' => 'Item',
			'StockTime' => 'Stock Time',
			'ShipTime' => 'Ship Time',
			'ManualQty' => 'Manual Qty',
		);
	}*/

    public static function findAll($fromDate, $toDate, $periodFrom, $periodTo, $start, $limit, $page, $filters=array())
    {
        
        //criteria simple.
        $criteria = new CDbCriteria;
        $criteria->order = 'Code ASC';
        foreach($filters as $col => $val){
            $criteria->addInCondition($col, $val);
            
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
        
        // Calculate Items Statistics
        $reqDet = RequestDetail::getItemDetails($Items, $fromDate, $toDate, $periodFrom, $periodTo);
        
        return array("orderlist" => $reqDet, "totalCount" => $totalCount);

    }

	function getItemDetails(&$Items, $fromDate, $toDate, $periodFrom, $periodTo, $params = Array())
	{
		$c = 0;
        $itStat = null;
        
		 foreach ($Items as $item)
        {
            $itStat[$c] = new ItemStatistics;
            $itStat[$c]->item= $item;
            ItemStatistics::find($itStat[$c], $fromDate, $toDate, $periodFrom, $periodTo);
            
            
        	$reqDet[$c] = new RequestDetail;
            

		    $reqDet[$c]->id = $c;
		    $reqDet[$c]->request_id = 0;
		    $reqDet[$c]->item_id = $itStat[$c]->item->id; //Custom period defaults to 3 months;
		    $reqDet[$c]->itemName = $itStat[$c]->item->Name;
		    $reqDet[$c]->Line = $itStat[$c]->item->Line;
		    $reqDet[$c]->Code = $itStat[$c]->item->Code;
		    $reqDet[$c]->pendingStock = $itStat[$c]->item->Incoming;
		   $reqDet[$c]->StockTime = 1;
		    if(isset($params[$c]['StockTime']))
		    	$reqDet[$c]->desiredStockTime = $params[$c]['StockTime'];
		    else
		    	$reqDet[$c]->desiredStockTime = 3;
		    if(isset($params[$c]['ShipTime']))
		    	$reqDet[$c]->ShipTime = $params[$c]['ShipTime'];
		    else
		    	$reqDet[$c]->ShipTime = $itStat[$c]->item->ShipDays/30;
		    if(isset($params[$c]['ManualQty']))
		    	$reqDet[$c]->ManualQty = $params[$c]['ManualQty'];
		    else
		    	$reqDet[$c]->ManualQty = 0;
		   
		    if($itStat[$c]->stdDev == 0) 
		    	$itStat[$c]->stdDev = 0.0000001;
		    if($itStat[$c]->periodStdDev == 0) 
		    	$itStat[$c]->periodStdDev = 0.0000001;
		    if($itStat[$c]->trendStdDev == 0) 
		    	$itStat[$c]->trendStdDev = 0.0000001;
		    
		    $reqDet[$c]->estimation1 = $itStat[$c]->mean;
		    $reqDet[$c]->estimation2 = $itStat[$c]->periodMean;
		    $reqDet[$c]->estimation3 = $itStat[$c]->trendMean;
		    
		    $sum = 0;
		    
		    if($reqDet[$c]->estimation1)
		    {
		    	$sum += (1 / $itStat[$c]->stdDev);
		    	$tc1 = (1 / $itStat[$c]->stdDev);
		    }else 
		    	$tc1 = 0;
		    if($reqDet[$c]->estimation2)
		    {
		    	$sum += (1/$itStat[$c]->periodStdDev);
		    	$tc2 = (1 / $itStat[$c]->periodStdDev);
		    }else 
		    	$tc2 = 0;
		    if($reqDet[$c]->estimation3)
		    {
		    	$sum += (1/$itStat[$c]->trendStdDev);
		    	$tc3 = (1 / $itStat[$c]->trendStdDev);
		    }else 
		    	$tc3 = 0;
		    
		    if($sum != 0){
		    $reqDet[$c]->weight1 = $tc1 / $sum;
		    
		    $reqDet[$c]->weight2=  $tc2 / $sum;
		    
		    $reqDet[$c]->weight3 =  $tc3 / $sum;
		    }
		    
		    else
		    	
		    {$reqDet[$c]->weight1 = 0;
		    
		    $reqDet[$c]->weight2=  0;
		    
		    $reqDet[$c]->weight3 =  1;}
		    $reqDet[$c]->estimatedSales = ($reqDet[$c]->estimation1 * $reqDet[$c]->weight1) + ($reqDet[$c]->estimation2 * $reqDet[$c]->weight2) + ($reqDet[$c]->estimation3 * $reqDet[$c]->weight3 );
		    $reqDet[$c]->currentStock = $itStat[$c]->meanWithinSales; // Media de los meses en los que SE vendio.
		    if( $reqDet[$c]->estimatedSales != 0)
		    	$reqDet[$c]->StockTime = $reqDet[$c]->currentStock / $reqDet[$c]->estimatedSales; // La estimacion es para el mes siguiente puede variar por temporada
		    else
		    	$reqDet[$c]->StockTime = -1;
		    $reqDet[$c]->price = $itStat[$c]->item->Price;
		     
		    $reqDet[$c]->stockBreaksCount;
		    $reqDet[$c]->orderTotal; // Media de tendencias.
		  //  $reqDet[$c]->desiredStockTime = 3;
		    $reqDet[$c]->desiredStock = $reqDet[$c]->desiredStockTime * $reqDet[$c]->estimatedSales;
		    if($reqDet[$c]->desiredStock > ($reqDet[$c]->currentStock + $reqDet[$c]->pendingStock))
		    	$reqDet[$c]->suggestedQty = $reqDet[$c]->desiredStock - ($reqDet[$c]->currentStock + $reqDet[$c]->pendingStock);
		    else
		    	$reqDet[$c]->suggestedQty = 0;
    		$c++;
        }
		return $reqDet;
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	/*public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id,true);
		$criteria->compare('request_id',$this->request_id,true);
		$criteria->compare('item_id',$this->item_id);
		$criteria->compare('StockTime',$this->StockTime,true);
		$criteria->compare('ShipTime',$this->ShipTime,true);
		$criteria->compare('ManualQty',$this->ManualQty,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}*/
}
