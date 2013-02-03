<?php

/**
 * This is the model class for table "Item".
 *
 * The followings are the available columns in table 'Item':
 * @property integer $id
 * @property string $Code
 * @property string $Name
 * @property string $Brand
 * @property string $Unit
 * @property string $Unit2
 * @property string $Branch
 * @property string $Line
 * @property string $Country
 * @property string $ArtType
 * @property string $Incoming
 * @property string $Price
 * @property string $Currency
 * The followings are the available model relations:
 * @property Transaction[] $transactions
 */

class Item extends CActiveRecord
{
    /**
     * Returns the static model of the specified AR class.
     * @return Item the static model class
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
        return 'Item';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('Code, Brand', 'length', 'max'=>20),
            array('Name', 'length', 'max'=>100),
            array('Unit, Unit2', 'length', 'max'=>10),
            array('Branch, Line, Country, ArtType', 'length', 'max'=>45),
            // The following rule is used by search().
            // Please remove those attributes that should not be searched.
            array('id, Code, Name, Brand, Unit, Unit2, Branch, Line, Country, ArtType', 'safe', 'on'=>'search'),
        );
    }


    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'transactions' => array(self::HAS_MANY, 'Transaction', 'item_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'Code' => 'Code',
            'Name' => 'Name',
            'Brand' => 'Brand',
            'Unit' => 'Unit',
            'Unit2' => 'Unit2',
            'Branch' => 'Branch',
            'Line' => 'Line',
            'Country' => 'Country',
            'ArtType' => 'Art Type',
            'Incoming' => 'Incoming',
            'Price' => 'Price',
            'Currency' => 'Currency'
        );
    }
    
    public static function getCategory($category)
    {
        $criteria=new CDbCriteria;
        $criteria->select=$category;  // only select the 'title' column
        $criteria->distinct=true;
        $rs = Item::model()->findAll($criteria); // $params is not needed
        foreach ($rs as $row) 
        {
            $retVal[]["value"]=$row[$category];
        }
        return $retVal;
    }
    public function getStock()
    {
    	$criteria=new CDbCriteria;
	    $criteria->select="SUM(Qty) AS Qty";
	    $criteria->condition="(Oper <> '1' OR OperDate = '0000-00-00' OR (Oper = 1 AND AffectStock = 1)) AND item_id = '".$this->id."'";
		$stock = Transaction::model()->query($criteria); // Este muchacho viene cargado !
		return $stock->Qty;
    }
    public static function getBranches()
    {
        return Item::getCategory('Branch');
    }
    public static function getBrands()
    {
        return Item::getCategory('Brand');
    }
    public static function getArtTypes()
    {
        return Item::getCategory('ArtType');
    }
    public static function getLines()
    {
        return Item::getCategory('Line');
    }

    public static function getCountries()
    {
        return Item::getCategory('Country');
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search()
    {
        // Warning: Please modify the following code to remove attributes that
        // should not be searched.

        $criteria=new CDbCriteria;

        $criteria->compare('id',$this->id);
        $criteria->compare('Code',$this->Code,true);
        $criteria->compare('Name',$this->Name,true);
        $criteria->compare('Brand',$this->Brand,true);
        $criteria->compare('Unit',$this->Unit,true);
        $criteria->compare('Unit2',$this->Unit2,true);
        $criteria->compare('Branch',$this->Branch,true);
        $criteria->compare('Line',$this->Line,true);
        $criteria->compare('Country',$this->Country,true);
        $criteria->compare('ArtType',$this->ArtType,true);
		$criteria->compare('Incoming',$this->Incoming,true);
		$criteria->compare('Price',$this->Price,true);
		$criteria->compare('Currency',$this->Currency,true);
        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }
}
