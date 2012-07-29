<?php

/**
 * This is the model class for table "Request".
 *
 * The followings are the available columns in table 'Request':
 * @property string $id
 * @property string $Date
 * @property string $PeriodStart
 * @property string $PeriodEnd
 * @property string $SubPeriod
 * @property string $Enabled
 */
class Request extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Request the static model class
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
		return 'Request';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Date, PeriodStart, PeriodEnd, SubPeriod', 'required'),
			array('Enabled', 'length', 'max'=>1),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, Date, PeriodStart, PeriodEnd, SubPeriod, Enabled', 'safe', 'on'=>'search'),
			//array('Date', 'date', 'format' => 'yyyy-MM-dd hh:mm:ss'), 
			//array('PeriodStart', 'date', 'format' => 'yyyy-MM-dd hh:mm:ss'), 
			//array('PeriodEnd', 'date', 'format' => 'yyyy-MM-dd hh:mm:ss'), 
			//array('SubPeriod', 'date', 'format' => 'yyyy-MM-dd hh:mm:ss'), 
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
			'Date' => 'Date',
			'PeriodStart' => 'Period Start',
			'PeriodEnd' => 'Period End',
			'SubPeriod' => 'Sub Period',
			'Enabled' => 'Enabled',
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
		$criteria->compare('Date',$this->Date,true);
		$criteria->compare('PeriodStart',$this->PeriodStart,true);
		$criteria->compare('PeriodEnd',$this->PeriodEnd,true);
		$criteria->compare('SubPeriod',$this->SubPeriod,true);
		$criteria->compare('Enabled',$this->Enabled,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
