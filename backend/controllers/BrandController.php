<?php
/**
 * 品牌
 */

namespace backend\controllers;

use backend\models\Brand;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\UploadedFile;
use backend\filters\RbacFilter;

class BrandController extends Controller{
        public function actionIndex(){
            $query=Brand::find();
            //实例化分页工具类
            $page=new Pagination([
                'totalCount'=>$query->count(),
                'defaultPageSize'=>3
            ]);
            $brands=$query->limit($page->limit)->offset($page->offset)->all();
            return $this->render('index',['brands'=>$brands,'page'=>$page]);
        }

        //添加
    public function actionAdd(){
            $model=new Brand();
            if($model->load(\Yii::$app->request->post()) && $model->validate()){
                $model->imgFile=UploadedFile::getInstance($model,'imgFile');
                if($model->imgFile){
                    $path=\Yii::getAlias('@webroot').'/upload/'.date('Ymd');
                    if(!is_dir($path)){
                        mkdir($path);
                    }
                    $fileName='/upload/'.date('Ymd').'/'.uniqid().'.'.$model->imgFile->extension;
                    $model->imgFile->saveAs(\Yii::getAlias('@webroot').$fileName,false);
                    $model->logo=$fileName;
                }
                $model->save(false);
                \Yii::$app->session->setFlash('添加成功','success');
                return $this->redirect(['brand/index']);
            }else{
                return $this->render('add',['model'=>$model]);
            }
    }
    //修改
    public function actionEdit($id){
            $model=Brand::findOne(['id'=>$id]);
            if($model->load(\Yii::$app->request->post()) && $model->validate()){
                $model->imgFile=UploadedFile::getInstance($model,'imgFile');
                if($model->imgFile){
                    $path=\Yii::getAlias('@webroot').'/upload/'.date('Ymd');
                    if(!is_dir($path)){
                        mkdir($path);
                    }
                    $fileName='/upload/'.date('Ymd').'/'.uniqid().'.'.$model->imgFile->extension;
                    $model->imgFile->saveAs(\Yii::getAlias('@webroot').$fileName,false);
                    $model->logo=$fileName;
                }
                $model->save(false);
                \Yii::$app->session->setFlash('修改成功','success');
                return $this->redirect(['brand/index']);
            }else{
                return $this->render('add',['model'=>$model]);
            }
    }
    //删除
    public function actionDel($id){
            $model=Brand::findOne(['id'=>$id]);
            if($model){
                $model->is_deleted=-1;
                $model->save();
            }
            return $this->redirect('index');
    }
    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>RbacFilter::className(),
            ]
        ];
    }
}