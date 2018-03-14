<?php
/**
 * Created by PhpStorm.
 * User: HXD
 * Date: 2018/3/2
 * Time: 16:53
 */

namespace backend\controllers;


use backend\models\Admin;
use backend\models\LoginForm;
use backend\models\PasswordForm;
use yii\data\Pagination;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use backend\filters\RbacFilter;

class AdminController extends Controller
{
    /*
     * 实现登录功能的前提   1、实现一个认证接口类   2、配置
     */

    //管理员的展示列表
    public function actionIndex(){
        $query=Admin::find();
        $pager=new Pagination([
            'totalCount'=>$query->count(),
            'defaultPageSize'=>2
        ]);
        $models=$query->limit($pager->limit)->offset($pager->offset)->all();
        return $this->render('index',['models'=>$models,'pager'=>$pager]);
    }

    //管理员的添加
    public function actionAdd(){
        //添加 指定场景
        $model=new Admin(['scenario'=>Admin::SCENARIO_ADD]);
        //提交方式和验证方法的验证
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $model->save();
            \Yii::$app->session->setFlash('添加成功','success');
            return $this->redirect(['admin/index']);
            //若跳转到当前页是
           //return $this->refresh();
        }
        return $this->render('add',['model'=>$model]);
    }

    //管理员的修改
    public function actionEdit($id){
        $model=Admin::findOne(['id'=>$id]);
        if($model==null){
            throw new NotFoundHttpException('用户不存在');
        }
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $model->save();
            \Yii::$app->session->setFlash('修改成功','success');
            return $this->redirect(['admin/index']);
        }
        return $this->render('add',['model'=>$model]);
    }

    //管理员的删除
    public function actionDel($id){
        $model=Admin::findOne(['id'=>$id]);
        if($model==null){
            throw new NotFoundHttpException('用户不存在');
        }else{
            $model->delete($id);
        }
        \Yii::$app->session->setFlash('删除成功','success');
        return $this->redirect(['admin/index']);
    }

    //修改密码
    public function actionPassword(){
        if(\Yii::$app->user->isGuest){
            return $this->redirect(['admin/login']);
        }
        $model=new PasswordForm();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //验证通过 修改密码
            $admin=\Yii::$app->user->identity;
            $admin->password=$model->newPassword;
            $admin->save();
            \Yii::$app->session->setFlash('密码修改成功','success');
            return $this->redirect(['index']);
        }
        return $this->render('password',['model'=>$model]);
    }
    //用户登录
    public function actionLogin(){
/*        $admin=Admin::findOne(['id'=>1]);
        //自动登录 login的第二个参数是设置有效期
        \Yii::$app->user->login($admin,7*24*3600);
        return $this->redirect(['user']);*/
        $model=new LoginForm();
        if ($model->load(\Yii::$app->request->post()) && $model->validate()){
            if ($model->login()){
                \Yii::$app->session->setFlash('登录成功','success');
                return $this->redirect(['index']);
            }
        }
        return $this->render('login',['model'=>$model]);
    }
    //用户退出
    public function actionLogout(){
        \Yii::$app->user->logout();
        return $this->redirect(['login']);
    }

    //测试用户登录
    public function actionUser(){
        var_dump(\Yii::$app->user->identity);
    }

    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>RbacFilter::className(),
                'only'=>['index','add','edit','del'],
            ]
        ];
    }

}