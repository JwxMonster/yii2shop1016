<?php
/**
 * 文章分类
 */

namespace backend\controllers;


use backend\models\ArticleCategory;
use yii\data\Pagination;
use yii\web\Controller;
use backend\filters\RbacFilter;
use yii\web\NotFoundHttpException;

class ArticleCategoryController extends Controller
{
    public function actionIndex(){
        $query=ArticleCategory::find();
        $page=new Pagination([
           'totalCount'=>$query->count(),
            'defaultPageSize'=>2
        ]);
        $models=$query->offset($page->offset)->limit($page->limit)->all();
        return $this->render('index',['models'=>$models,'page'=>$page]);
    }
    //添加
    public function actionAdd(){
        $model=new ArticleCategory();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $model->save();
            \Yii::$app->session->setFlash('添加成功','success');
            return $this->redirect('index');
        }else{
            return $this->render('add',['model'=>$model]);
        }
    }
    //删除
    public function actionDel($id){
        $model=ArticleCategory::findOne(['id'=>$id]);
        if ($model){
            $model->status=-1;
            $model->save();
        }
        $this->redirect('index');
    }
    //修改
    public function actionEdit($id){
        $model=ArticleCategory::findOne(['id'=>$id]);
        if($model==null){
            throw new NotFoundHttpException('分类不存在');
        }
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $model->save();
            \Yii::$app->session->setFlash('修改成功');
            return $this->redirect('index');
        }
        return $this->render('add',['model'=>$model]);
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