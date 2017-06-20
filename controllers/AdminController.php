<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\Admin;
use yii\web\UploadedFile;
use yii\web\Session;
use yii\web\Cookie;
use app\models\UploadFile as upload;


class AdminController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
			//行为，每次请求的时候都调用行为
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
		
		$cookie = new Cookie;
        $cookie = \Yii::$app->request->cookies;
		
		$model = new Admin();
		return $this->render('index', [
            'model' => $model,
			'active'=> 'class="active"',
        ]);
    }

	
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionAjaxSave()
    {
		//创建
        $model = new Admin();
		$model->file =  UploadedFile::getInstance($model, 'file');
		$model->title = Yii::$app->request->post('Admin')['title'];
		
		if($model->save()){
			return $this->redirect(['admin/list']);
		}else{
			return false;
		}
    }
	
	/**
     * Displays homepage.
     *
     * @return string
     */
    public function actionDelete()
    {
		$id = Yii::$app->request->get('id');
		$one = upload::find()->where(array("id"=>$id))->one();
		$one->delete();
		return $this->redirect(['admin/list']);
		
    }
	


	 /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionList()
    {
		$model = new Admin();
		$list  = $model->getList();
		//创建
		return $this->render('list',[
			'list'=>$list,
            'active'=> 'class="active"',
		]);
    }


	/**
     * 下载文件
     *
     */
	public function actionDownload()
	{
		$url = Yii::$app->request->get('url');
		$filename = Yii::$app->request->get('filename');
		$old_url = Yii::$app->request->get('old_url');
		
		
		if(empty($url)){
			$this->redirect(['admin/list']);
		}
		$fp=fopen($url,'r');
		header("Content-type: application/octet-stream"); 
		header("Accept-Ranges: bytes"); 
		header("Accept-Length: ".filesize('../'.$old_url)); 
		header("Content-Disposition: attachment; filename=".$filename); 
		//输出文件内容 
		ob_clean();
        flush();
        //=================重点===================
        //设置分流
        $buffer=1024;
        //来个文件字节计数器
        $count=0;
        while(!feof($fp)&&(filesize('../'.$old_url)-$count>0)){
            $data=fread($fp,$buffer);
            $count+=$data;//计数
            echo $data;//传数据给浏览器端
        }

        fclose($fp);
	}
}

