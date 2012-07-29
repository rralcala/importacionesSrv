<?php

/**
 * This is the model class for table "Transaction".
 *
 * The followings are the available columns in table 'Transaction':
 * @property string $id
 * @property integer $Oper
 * @property integer $item_id
 * @property string $Qty
 * @property string $Unit
 * @property string $RowTotal
 * @property string $TransDate
 * @property string $OperDate
 * @property string $StockDepo
 * @property string $Currency
 * @property double $CurrencyRate
 * @property integer $AffectStock
 * @property integer $Enable
 *
 * The followings are the available model relations:
 * @property Item $item
 */
class Transaction extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Transaction the static model class
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
		return 'Transaction';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Oper, item_id, Qty, Unit, RowTotal, TransDate, OperDate, StockDepo, Currency, CurrencyRate', 'required'),
			array('Oper, item_id, AffectStock, Enable', 'numerical', 'integerOnly'=>true),
			array('CurrencyRate', 'numerical'),
			array('Qty, Unit', 'length', 'max'=>10),
			array('RowTotal', 'length', 'max'=>14),
			array('StockDepo', 'length', 'max'=>20),
			array('Currency', 'length', 'max'=>3),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, Oper, item_id, Qty, Unit, RowTotal, TransDate, OperDate, StockDepo, Currency, CurrencyRate, AffectStock, Enable', 'safe', 'on'=>'search'),
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
			'item' => array(self::BELONGS_TO, 'Item', 'item_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'Oper' => 'Oper',
			'item_id' => 'Item',
			'Qty' => 'Qty',
			'Unit' => 'Unit',
			'RowTotal' => 'Row Total',
			'TransDate' => 'Trans Date',
			'OperDate' => 'Oper Date',
			'StockDepo' => 'Stock Depo',
			'Currency' => 'Currency',
			'CurrencyRate' => 'Currency Rate',
			'AffectStock' => 'Affect Stock',
			'Enable' => 'Enable',
		);
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('Oper',$this->Oper);
		$criteria->compare('item_id',$this->item_id);
		$criteria->compare('Qty',$this->Qty,true);
		$criteria->compare('Unit',$this->Unit,true);
		$criteria->compare('RowTotal',$this->RowTotal,true);
		$criteria->compare('TransDate',$this->TransDate,true);
		$criteria->compare('OperDate',$this->OperDate,true);
		$criteria->compare('StockDepo',$this->StockDepo,true);
		$criteria->compare('Currency',$this->Currency,true);
		$criteria->compare('CurrencyRate',$this->CurrencyRate);
		$criteria->compare('AffectStock',$this->AffectStock);
		$criteria->compare('Enable',$this->Enable);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}