<?php

/**
 * This is the model class for table "Request".
 *
 * The followings are the available columns in table 'Request':
 * @property string $id
 * @property string $Date
 * @property string $Description
 * @property string $P1Start
 * @property string $P1End
 * @property string $P2Start
 * @property string $P2End
 * @property string $Enabled
 * @property string $user_id
 */
class Request extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
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
			array('Date, Description, P1Start, P1End, P2Start, P2End', 'required'),
			array('Description', 'length', 'max'=>32),
			array('Enabled', 'length', 'max'=>1),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, Date, Description, P1Start, P1End, P2Start, P2End, Enabled', 'safe', 'on'=>'search'),
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
			'Description' => 'Description',
			'P1Start' => 'P1 Start',
			'P1End' => 'P1 End',
			'P2Start' => 'P2 Start',
			'P2End' => 'P2 End',
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
		$criteria->compare('Description',$this->Description,true);
		$criteria->compare('P1Start',$this->P1Start,true);
		$criteria->compare('P1End',$this->P1End,true);
		$criteria->compare('P2Start',$this->P2Start,true);
		$criteria->compare('P2End',$this->P2End,true);
		$criteria->compare('Enabled',$this->Enabled,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}
