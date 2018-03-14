<?php
/**
 * Created by PhpStorm.
 * User: HXD
 * Date: 2018/2/12
 * Time: 16:40
 */

namespace backend\controllers;


use backend\models\Goods;
use backend\models\GoodsDayCount;
use backend\models\GoodsIntro;
use backend\models\GoodsGallery;
use backend\models\GoodsSearchForm;
use yii\data\Pagination;
use yii\web\Controller;
use flyok666\uploadifive\UploadAction;
use yii\web\NotFoundHttpException;
use backend\filters\RbacFilter;


class GoodsController extends Controller
{
    //商品列表展示(搜索，分页)
    public function actionIndex(){
        $model=new GoodsSearchForm();
        $query=Goods::find();
        //接收表单提交的参数
        $model->search($query);
        $page=new Pagination([
            'totalCount'=>$query->count(),
            'defaultPageSize'=>2
        ]);
        $models=$query->limit($page->limit)->offset($page->offset)->all();
        return $this->render('index',['page'=>$page,'model'=>$model,'models'=>$models]);
    }

    //商品的添加
    //新增商品自动生成sn,规则为年月日+今天的第几个商品,比如2016053000001
    public function actionAdd()
    {
        //实例化表单模型
        $model = new Goods();
        $introModel = new GoodsIntro();
        //判断数据接收的方式
        if($model->load(\Yii::$app->request->post()) && $introModel->load(\Yii::$app->request->post())){
            if($model->validate() && $introModel->validate()){
                //自动生成商品货号，日期加时间，类型日期+000？
                $day = date('Ymd');
                $goodsCount = GoodsDayCount::findOne(['day'=>$day]);
                //var_dump($goodsCount);exit;
                if($goodsCount==null){
                    $goodsCount = new GoodsDayCount();
                    $goodsCount->day = $day;
                    $goodsCount->count = 0;
                }
                //字符串长度补全
                //sprintf返回一个格式化字符串
                //$model->sn = date('Ymd').sprintf("%04d",$goodsCount->count+1);
               /* $model->sn=date('Ymd').substr('000'.($goodsCount->count+1),-4,4);
                $model->save();*/
                $count = ++$goodsCount->count;
                //var_dump($count);exit;
                $model->sn = $day.str_pad($count,4,0,STR_PAD_LEFT);
                //var_dump($model->sn);exit;
                $model->save();
                $introModel->goods_id = $model->id;
                $introModel->save();
                $goodsCount->save();
                //跳转
                \Yii::$app->session->setFlash('success','商品添加成功,请添加商品相册');
                return $this->redirect(['goods/gallery','id'=>$model->id]);
            }
        }
        //跳转
        return $this->render('add',['model'=>$model,'introModel'=>$introModel]);
    }

    //商品的修改
    public function actionEdit($id){
        $model=Goods::findOne(['id'=>$id]);
        $introModel=$model->goodsIntro;
        if($model->load(\Yii::$app->request->post()) && $introModel->load(\Yii::$app->request->post())){
            if($model->validate() && $introModel->validate()){
                $model->save();
                $introModel->save();
                \Yii::$app->session->setFlash('修改成功','success');
                return $this->redirect(['goods/index']);
            }
        }
        return $this->render('add',['model'=>$model,'introModel'=>$introModel]);
    }
    //商品的删除
    public function actionDel($id){
        $model=Goods::findOne(['id'=>$id]);
        if($model==null){
            throw new NotFoundHttpException('商品不存在');
        }else{
            $model->delete($id);
        }
        \Yii::$app->session->setFlash('删除成功','success');
        return $this->redirect(['goods/index']);
    }

    //商品相册
    public function actionGallery($id){
        $goods=Goods::findOne(['id'=>$id]);
        if($goods==null){
            throw new NotFoundHttpException('商品不存在');
        }
        return $this->render('gallery',['goods'=>$goods]);
    }

    //预览
    public function actionView($id)
    {
        $model = Goods::findOne(['id'=>$id]);
        if($model==null){
            throw new NotFoundHttpException('商品不存在');
        }
        return $this->render('view',['model'=>$model]);

    }

    public function actions() {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
                'config' => [
                    "imageUrlPrefix"  => "http://www.yiishop.com",//图片访问路径前缀
                    "imagePathFormat" => "/upload/{yyyy}{mm}{dd}/{time}{rand:6}" ,//上传保存路径
                    "imageRoot" => \Yii::getAlias("@webroot"),
                ],
            ],
            's-upload' => [
                'class' => UploadAction::className(),
                'basePath' => '@webroot/upload',
                'baseUrl' => '@web/upload',
                'enableCsrf' => true, // default
                'postFieldName' => 'Filedata', // default
                //BEGIN METHOD
                //'format' => [$this, 'methodName'],
                //END METHOD
                //BEGIN CLOSURE BY-HASH
                'overwriteIfExist' => true,//如果文件已存在，是否覆盖
                /* 'format' => function (UploadAction $action) {
                     $fileext = $action->uploadfile->getExtension();
                     $filename = sha1_file($action->uploadfile->tempName);
                     return "{$filename}.{$fileext}";
                 },*/
                //END CLOSURE BY-HASH
                //BEGIN CLOSURE BY TIME
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filehash = sha1(uniqid() . time());
                    $p1 = substr($filehash, 0, 2);
                    $p2 = substr($filehash, 2, 2);
                    return "{$p1}/{$p2}/{$filehash}.{$fileext}";
                },//文件的保存方式
                //END CLOSURE BY TIME
                'validateOptions' => [
                    'extensions' => ['jpg', 'png'],
                    'maxSize' => 1 * 1024 * 1024, //file size
                ],
                'beforeValidate' => function (UploadAction $action) {
                    //throw new Exception('test error');
                },
                'afterValidate' => function (UploadAction $action) {},
                'beforeSave' => function (UploadAction $action) {},
                'afterSave' => function (UploadAction $action) {
                    $goods_id = \Yii::$app->request->post('goods_id');
                    if($goods_id){
                        $model = new GoodsGallery();
                        $model->goods_id = $goods_id;
                        $model->path = $action->getWebUrl();
                        $model->save();
                        $action->output['fileUrl'] = $model->path;
                        $action->output['id'] = $model->id;
                    }else{
                        $action->output['fileUrl'] = $action->getWebUrl();//输出文件的相对路径
                    }
                },
            ],
        ];
    }

    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>RbacFilter::className(),
                'except'=>['upload','s-upload','view']
            ]
        ];
    }
}
