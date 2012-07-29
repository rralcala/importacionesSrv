<?php

/**
 * This is the model class for table "SalesByDay".
 *
 * The followings are the available columns in table 'SalesByDay':
 * @property integer $item_id
 * @property string $OperDate
 * @property string $Unit
 * @property string $Qty
 */
class SalesByDay extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return SalesByDay the static model class
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
		return 'SalesByDay';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('item_id, OperDate, Unit', 'required'),
			array('item_id', 'numerical', 'integerOnly'=>true),
			array('Unit', 'length', 'max'=>10),
			array('Qty', 'length', 'max'=>32),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('item_id, OperDate, Unit, Qty', 'safe', 'on'=>'search'),
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
			'item_id' => 'Item',
			'OperDate' => 'Oper Date',
			'Unit' => 'Unit',
			'Qty' => 'Qty',
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

		$criteria->compare('item_id',$this->item_id);
		$criteria->compare('OperDate',$this->OperDate,true);
		$criteria->compare('Unit',$this->Unit,true);
		$criteria->compare('Qty',$this->Qty,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}