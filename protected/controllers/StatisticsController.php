<?php

class StatisticsController extends Controller
{
    /** I think this will not be required
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
            array('allow',  // allow authenticated users to perform 'index' and 'view' actions
                'actions'=>array('index','view','list'),
                'users'=>array('@'),
            ),
            /*array('allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions'=>array('create','update'),
                'users'=>array('@'),
            ),*/
            array('allow', // allow admin user to perform 'admin' 
                'actions'=>array('admin'),
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
        echo(CJSON::encode ($this->loadModel($id)));
    /*  $this->render('view',array(
            'model'=>$this->loadModel($id),
        ));*/
    }


    public function actionList($page, $start, $limit, $filter='', $p1start, $p2start, $p1end, $p2end)
    {
        // Falta pulir !
        $filters = Filter::process_filter($filter); //para item.
        
        $models = ItemStatistics::findAll($p1start, $p1end, $p2start, $p2end, $start, $limit, $page, $filters);

        $return = array();
        $return['success'] = true;
        // Agregar aca los mismos criterios de busqueda que se apliquen a findAll
        $return['totalCount'] = $models["totalCount"]; //Item::model()->count();//$criteria
        $return['statistics'] = $models["resultSet"];
        echo CJSON::encode($return);
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    /*public function actionCreate()
    {
        $model=new Item;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['Item']))
        {
            $model->attributes=$_POST['Item'];
            if($model->save())
                $this->redirect(array('view','id'=>$model->id));
        }

        $this->render('create',array(
            'model'=>$model,
        ));
    }*/

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    /*public function actionUpdate($id)
    {
        $model=$this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if(isset($_POST['Item']))
        {
            $model->attributes=$_POST['Item'];
            if($model->save())
                $this->redirect(array('view','id'=>$model->id));
        }

        $this->render('update',array(
            'model'=>$model,
        ));
    }*/

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    /*public function actionDelete($id)
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
    }*/

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $dataProvider=new CActiveDataProvider('Item');
        $this->render('index',array(
            'dataProvider'=>$dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model=new Item('search');
        $model->unsetAttributes();  // clear any default values
        if(isset($_GET['Item']))
            $model->attributes=$_GET['Item'];

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
        $x = 3;
        $from = new DateTime;
        $to = new DateTime;
        $xPeriod = new DateTime;
        $from->sub(new DateInterval("P12M"));
        $to->sub(new DateInterval("P1M"));
//        error_log("loadModel()->From".$from);
        $xPeriod->sub(new DateInterval("P".$x."M"));
        //Calcular el Ultimo dia del mes
        $result = strtotime($to->format('Y')."-".$to->format('m')."-01");
        $result = date('Y-m-d',strtotime('-1 second', strtotime('+1 month', $result)));
        $model=ItemStatistics::find($id, $from->format('Y').'-'.$from->format('m').'-01', $result, $xPeriod->format('Y').'-'.$xPeriod->format('m').'-01');
        
    
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
        if(isset($_POST['ajax']) && $_POST['ajax']==='item-form')
        {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
