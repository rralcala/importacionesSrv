<?php

class RequestDetailController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	//public $layout='//layouts/column2';

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
				'actions'=>array('getDetail','create','update', 'delete', 'list', 'jsonupdate', 'jsoncreate', 'jsondelete'),
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
		$model=new RequestDetail;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['RequestDetail']))
		{
			$model->attributes=$_POST['RequestDetail'];
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

		if(isset($_POST['RequestDetail']))
		{
			$model->attributes=$_POST['RequestDetail'];
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
		$dataProvider=new CActiveDataProvider('RequestDetail');
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new RequestDetail('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['RequestDetail']))
			$model->attributes=$_GET['RequestDetail'];

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
		$model=RequestDetail::model()->findByPk($id);
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
		if(isset($_POST['ajax']) && $_POST['ajax']==='request-detail-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	/**
	 * Return de Details about an Order
	 **/
	public function actionGetDetail($id) 
	{
		$return = array(); 
		if($id != '')
		{	
			$request = Request::model()->findByPk($id);
			$models = OrderDetail::model()->findAll('request_id=:request_id', array(':request_id'=>$id));
	        foreach($models as $item)
	        {
	        	
	        	$temp['StockTime'] = $item->StockTime;
	        	$temp['ShipTime'] = $item->ShipTime;
	        	$temp['ManualQty']= $item->ManualQty;
	        	$Items [] = Item::model()->findByPk($item->item_id);
	        	$params [] = $temp;
	        	
	        }
	        $reqDet = RequestDetail::getItemDetails($Items, $request->P1Start, $request->P1End, $request->P2Start, $request->P2End, $params);
	        $return['success'] = true;
	        // Agregar aca los mismos criterios de busqueda que se apliquen a findAll
	        $return['totalCount'] = count($reqDet);//$models["totalCount"]; //Item::model()->count();//$criteria
	        $return['orderDetails'] = $reqDet;
		}
        else
        {
        	$return['success'] = false;
        	$return['totalCount'] = 0;//$models["totalCount"]; //Item::model()->count();//$criteria
        	$return['orderDetails'] = Array();
        }
        

        echo CJSON::encode($return);

	}


	/**
	 * List all RequestDetails for the given Request.
	 **/
	public function actionList($start, $limit, $page, $p1start, $p2start, $p1end, $p2end, $filter='') {
		//pagination parameters
		        // Falta pulir !
		/*        $page = isset($_GET['page']) ? $_GET['page'] : 1;
		$start = isset($_GET['start']) ? $_GET['start'] : 0;
		$limit = isset($_GET['limit']) ? $_GET['limit'] : 200;
		$filter = isset($_GET['filter']) ? $_GET['filter'] : '';*/
        $filters = Filter::process_filter($filter); //para item.
        
        $x = 3;
        $models = RequestDetail::findAll($p1start, $p1end, $p2start,  $p2end, $start, $limit, $page, $filters);

        $return = array();
        $return['success'] = true;
        // Agregar aca los mismos criterios de busqueda que se apliquen a findAll
        $return['totalCount'] = $models["totalCount"]; //Item::model()->count();//$criteria
        $return['orderDetails'] = $models["orderlist"];
        echo CJSON::encode($return);
        
	/*	 

		$filters = Filter::process_filter($filter); //para item.
        $criteria = new CDbCriteria;
        
        foreach($filters as $col => $val){
            $criteria->addInCondition($col, $val);
        }

		$dataProvider = new CActiveDataProvider('RequestDetail', array(
			'criteria' => $criteria,
			'pagination' => array(
				'pageSize' => $limit,
				'currentPage' => $page
				
			),
		));
		$details = $dataProvider->getData(true);
		foreach($details as $k => $det){
			$details[$k] = $det->toJSON();
		}
		$totalCount = $dataProvider->pagination->getItemCount();
		$details = implode(',', $details);
		echo "{success: true, orderdetails: [$details], totalCount: $totalCount}";*/
	}
	
	/**
	 * Creates a new RequestDetail.
	 **/
	public function actionJsonCreate() {
		$params = CJSON::decode($GLOBALS['HTTP_RAW_POST_DATA']);
		$ok = true;
		foreach($params['details'] as $p){
			$model = new RequestDetail;
			$model->attributes = $p;
			$model->item_id = $p['item']['id'];
			$model->request_id = $params['request_id'];
			if(!$model->save())
				$ok = false;
		}
		if($ok){
			echo "{success: true}";	
		}else{
			echo "{success: false}";
		}
	}
	
	/**
	 * Updates a existing RequestDetail.
	 **/
	public function actionJsonUpdate() {
		//TODO
	}
	
	/**
	 * Deletes an RequestDetail
	 **/
	public function actionJsonDelete() {
		//TODO
	}
}
