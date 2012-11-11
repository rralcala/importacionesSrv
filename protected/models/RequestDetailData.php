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
 */
class RequestDetailData extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
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
		return array(
			array('request_id, item_id, StockTime, ShipTime, ManualQty', 'required'),
			array('item_id', 'numerical', 'integerOnly'=>true),
			array('request_id', 'length', 'max'=>20),
			array('StockTime, ShipTime, ManualQty', 'length', 'max'=>10),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, request_id, item_id, StockTime, ShipTime, ManualQty', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'request_id' => 'Request',
			'item_id' => 'Item',
			'StockTime' => 'Stock Time',
			'ShipTime' => 'Ship Time',
			'ManualQty' => 'Manual Qty',
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
		$criteria->compare('request_id',$this->request_id,true);
		$criteria->compare('item_id',$this->item_id);
		$criteria->compare('StockTime',$this->StockTime,true);
		$criteria->compare('ShipTime',$this->ShipTime,true);
		$criteria->compare('ManualQty',$this->ManualQty,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}