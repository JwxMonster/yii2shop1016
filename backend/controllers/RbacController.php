<?php
/**
 * Created by PhpStorm.
 * User: HXD
 * Date: 2018/3/4
 * Time: 14:56
 */

namespace backend\controllers;


use backend\models\RbacForm;
use backend\models\RoleForm;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use backend\filters\RbacFilter;


class RbacController extends Controller
{
    public function actionIndex11(){
        /*
         * 1、实现RBAC需要配置authManager组件   2、通过数据迁移建表
         * 所有的RBAC都不需要直接操作数据表 都是通过authManager组件提供的方法来实现
         */

        //两个用户  admin  zhangsan
        //两个角色  超级管理员  前台
        //两个权限  添加用户 用户列表
        //超级管理员[添加用户 用户列表]  前台[用户列表]
        //admin--超级管理员   zhangsan--前台

        /*
         * 注：所有的RBAC都不需要操作数据表，全是通过authManager组件提供的方法来完成
         */
        $auth=\Yii::$app->authManager;
        //1、添加角色
        //(1)创建一个新角色
        $role=$auth->createRole('超级管理员');
        $role2=$auth->createRole('超级管理员');
        //(2)保存数据表
        $auth->add($role);
        $auth->add($role2);
        //添加权限(权限和路由一致)
        //创建一个新的权限
        $permission=$auth->createPermission('rbac/add-user');
        $permission2=$auth->createPermission('rbac/user-index');
        //保存
        $auth->add($permission);
        $auth->add($permission2);
        //给角色分配权限
        $auth->addChild($role,$permission);//角色 权限
        $auth->addChild($role,$permission2);
        $auth->addChild($role2,$permission2);
        //给用户分配角色
        $auth->assign($role,2);//角色 ID
        $auth->assign($role2,1);
        echo '设置完成';
    }
    public function actionTest1(){
        //测试用户是否拥有其权限
        $result=\Yii::$app->user->can('brand/index');
        var_dump($result);
    }

    //权限列表
    public function actionPermissionIndex(){
        //获取所有权限
        $models=\Yii::$app->authManager->getPermissions();
        return $this->render('permission-index',['models'=>$models]);
    }

    //添加权限
    public function actionPermissionAdd(){
        $model=new RbacForm();
        if ($model->load(\Yii::$app->request->post()) && $model->validate()){
            if($model->addPermission()){
                \Yii::$app->session->setFlash('添加成功','success');
                return $this->redirect(['permission-index']);
                //返回当前页
                //return $this->refresh();
            }
        }
        return $this->render('permission-add',['model'=>$model]);
    }

    //修改权限
    public function actionPermissionEdit($name){
        $permission=\Yii::$app->authManager->getPermission($name);
        if($permission==null){
            throw new NotFoundHttpException('权限不存在');
        }
        $model=new RbacForm();
        //将要修改的权限值赋值给表单模型
        $model->loadData($permission);
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            if($model->updatePermission($name)){
                \Yii::$app->session->setFlash('success','权限修改成功');
                return $this->redirect(['permission-index']);
            }else{
                var_dump($model->getErrors());exit;
            }
        }
        return $this->render('permission-add',['model'=>$model]);
    }
    //删除权限
    public function actionPermissionDel($name){
        $permission=\Yii::$app->authManager->getPermission($name);
        if($permission==null){
            throw new NotFoundHttpException('该权限不存在');
        }
        \Yii::$app->authManager->remove($permission);
        \Yii::$app->session->setFlash('success','删除成功');
        return $this->redirect(['permission-index']);
    }

    //====================================================================================================//
    //角色列表
    public function actionRoleIndex(){
        $models=\Yii::$app->authManager->getRoles();
        return $this->render('role-index',['models'=>$models]);
    }

    //角色的添加
    public function actionAddRole(){
        $model=new RoleForm();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            if($model->addRole()){
                \Yii::$app->session->setFlash('success','添加成功');
                return $this->redirect(['role-index']);
            }
        }
        return $this->render('role-add',['model'=>$model]);
    }

    //角色的修改
    public function actionEditRole($name){
        $role=\Yii::$app->authManager->getRole($name);
        if($role==null){
            throw new NotFoundHttpException('该用户不存在！');
        }
        $model=new RoleForm();
        $model->loadData($role);
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            if($model->updateRole($name)){
                \Yii::$app->session->setFlash('success','修改成功');
                return $this->redirect(['role-index']);
            }
        }
            return $this->render('role-add',['model'=>$model]);
    }

    //角色的删除
    public function actionDelRole($name){
        $role=\Yii::$app->authManager->getRole($name);
        if($role==null){
            throw new NotFoundHttpException('角色不存在');
        }
        \Yii::$app->authManager->remove($role);
        \Yii::$app->session->setFlash('success','角色删除成功');
        return $this->redirect(['role-index']);
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