<?php
/**
 * Created by PhpStorm.
 * User: HXD
 * Date: 2018/3/7
 * Time: 19:40
 */

namespace backend\controllers;


use backend\models\Menu;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use backend\filters\RbacFilter;

class MenuController extends Controller
{
    //菜单列表
    public function actionIndex(){
        $models=Menu::find()->where(['parent_id'=>0])->all();

        return $this->render('index',['models'=>$models]);
    }

    //菜单添加
    public function actionAdd(){
        $model=new Menu();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $model->save();
            \Yii::$app->session->setFlash('success','添加成功');
            return $this->redirect('index');
        }
        return $this->render('add',['model'=>$model]);
    }

    //菜单修改
    public function actionEdit($id){
        $model=Menu::findOne(['id'=>$id]);
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //预防出现三级菜单
            if($model->parent_id && !empty($model->children)){
                $model->addError('parent_id','只能为顶级菜单');
            }else{
                $model->save();
                return $this->redirect('index');
            }
        }
        return $this->render('add',['model'=>$model]);
    }

    //菜单删除
    public function actionDel($id){
        $model=Menu::findOne(['id'=>$id]);
        if($model==null){
            throw new NotFoundHttpException('该菜单不存在');
        }else{
            $model->delete($id);
        }
        \Yii::$app->session->setFlash('success','删除成功');
        return $this->redirect(['menu/index']);
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