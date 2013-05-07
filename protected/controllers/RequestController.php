<?php

class RequestController extends Controller
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
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update', 'delete', 'list', 'jsonupdate', 'jsoncreate', 'jsondelete'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin'/*,'delete'*/),
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
		$model=new Request;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Request']))
		{
			$model->attributes=$_POST['Request'];
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

		if(isset($_POST['Request']))
		{
			$model->attributes=$_POST['Request'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
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
		$dataProvider=new CActiveDataProvider('Request');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Request('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Request']))
			$model->attributes=$_GET['Request'];

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
		$model=Request::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='request-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	/**
	 * List all Requests
	 **/
	public function actionList($page, $start, $limit, $filter='') {
		//pagination parameters

		$filters = Filter::process_filter($filter); //para item.
		$filters['Enabled'] = array('A');
        $criteria = new CDbCriteria;
        
        foreach($filters as $col => $val){
            $criteria->addInCondition($col, $val);
        }
		
		$dataProvider = new CActiveDataProvider('Request', array(
			'criteria' => $criteria,
			'pagination' => array(
			'pageSize' => $limit,
			'currentPage' => $page
				
			),
		));
		$requests = array();
		$requests['success'] = true;
		$requests['orders'] = $dataProvider->getData(true);
		$requests['totalCount'] = $dataProvider->pagination->getItemCount();
		echo CJSON::encode($requests);
	}
	
	/**
	 * Creates a new Request.
	 **/
	public function actionJsonCreate() 
	{
		$retVal = true;
		$request = CJSON::decode(file_get_contents("php://input"));
		$params = $request[0];
 
		$params['Date'] = date('Y-m-d');
		
		$model = new Request;
		$model->attributes = $params;
		
		if($model->save())
		{
			foreach($request[1] as $detail)
			{
				$modelDetail = new RequestDetailData;
				$modelDetail['request_id'] = $model->id;
				$modelDetail['item_id'] = $detail['item_id'];
				$modelDetail['StockTime'] = round($detail['desiredStockTime'],2); //FFUUUUU
				$modelDetail['ShipTime'] = round($detail['ShipTime'],2);
				$modelDetail['ManualQty'] = round($detail['ManualQty'],2);
				
				if(!$modelDetail->save()){
					$retVal = false;
				}
				error_log(print_r($modelDetail->getErrors(),'true'));
			}
		}
		else
		{
			$retVal = false;
		}
		
		echo "{success: ".($retVal?'true':'false').", request_id: ".$model->id."}";
	}
	
	/**
	 * Updates a existing Request.
	 **/
	public function actionJsonUpdate() {
		//TODO
	}
	
	/**
	 * Deletes an Request.
	 **/
	public function actionJsonDelete() {
		//TODO
	}
}
