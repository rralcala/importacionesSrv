<?php

class UsersController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
	/*		array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),*/
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update', 'view', 'index', 'list', 'jsonupdate', 'jsoncreate', 'jsondelete', 'checkusername'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$this->render('view',array(
			'model'=>$this->loadModel($id),
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Users;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Users']))
		{
			$model->attributes=$_POST['Users'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Users']))
		{
			$model->attributes=$_POST['Users'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	protected function afterValidate()
	{
		parent::afterValidate();
		$this->password = $this->encrypt($this->password);
	}


	public function encrypt($value)
	{
		return md5($value);
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			$this->loadModel($id)->delete();

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Users');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Users('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Users']))
			$model->attributes=$_GET['Users'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Users::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='users-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	/**
	 *	List all users
	 **/
	public function actionList($page, $start, $limit, $filter='') {
		//pagination parameters

		$filters = Filter::process_filter($filter); //para item.
		$filters['State'] = array('A');
        $criteria = new CDbCriteria;
        
        foreach($filters as $col => $val){
            $criteria->addInCondition($col, $val);
        }

		
		$dataProvider = new CActiveDataProvider('Users', array(
			'criteria' => $criteria,
			'pagination' => array(
				'pageSize' => $limit,
				'currentPage' => $page
				
			),
		));
		$users = array();
		$users['success'] = true;
		$users['users'] = $dataProvider->getData(true);
		$users['totalCount'] = $dataProvider->pagination->getItemCount();
		echo CJSON::encode($users);
	}

	/**
	 *	Updates a particular model
	 **/
	public function actionJsonUpdate() {
		$params = CJSON::decode($GLOBALS['HTTP_RAW_POST_DATA']);

		$model = $this->loadModel($params['id']);
		$model->attributes = $params;
	
		// if a new password has been entered
		if ($model->new_password !== '') {
			$model->password = $this->encrypt($model->new_password);
		}
			
		if($model->save())
			echo "{success: true}";
	}
	
	/**
	 * Creates a new user
	 **/
	public function actionJsonCreate() {
		$params = CJSON::decode($GLOBALS['HTTP_RAW_POST_DATA']);
		$params['state'] = 'A';
		$model = new Users;
		$model->attributes = $params;
		$model->password = $this->encrypt($model->new_password);
		if($model->save())
			echo "{success: true}";
	}

	/**
	 * Deletes users.
	 * Sets user's state to 'B'.
	 **/
	public function actionJsonDelete() {
		$params = CJSON::decode($GLOBALS['HTTP_RAW_POST_DATA']);
		$ok = 'true';
		if(array_key_exists('id', $params)) //just one user to delete.
			$params = array($params);
		
		foreach($params as $p){
			$model = $this->loadModel($p['id']);
			$model->state = 'B';
			if(!$model->save())
				$ok = 'false';
		}
		echo "{success: $ok}";
	}
	
	
	/**
	 * Check whether the username exists.
	 **/
	public function actionCheckUsername(){
		$username = isset($_POST['username']) ? $_POST['username'] : '';
		$user = new Users;
		$user->username = $username;
		$provider =  $user->search();
		$provider->getData(true);
		$success = $provider->itemCount;
		echo "{success: $success}";
	}
}
