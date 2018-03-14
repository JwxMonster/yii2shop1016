<?php
/**
 * Created by PhpStorm.
 * User: HXD
 * Date: 2018/3/4
 * Time: 15:45
 */

namespace backend\models;


use yii\base\Model;
use yii\rbac\Permission;

class RbacForm extends Model
{
    public $name;//权限名称
    public $description;//权限描述

    public function rules()
    {
        return [
            [['name','description'],'required'],
            //['name','validateName']
        ];
    }

    public function attributeLabels()
    {
        return [
            'name'=>'权限名称(路由)',
            'description'=>'权限描述'
        ];
    }

    //自定义验证规则
   /* public function validateName(){
        if(\Yii::$app->authManager->getPermission($this->name)){
            $this->addError('权限已经存在');
        }
    }*/

    //添加权限
    public function addPermission(){
        $auth=\Yii::$app->authManager;
        //创建权限
        //创建前检查权限是否已经存在
        if($auth->getPermission($this->name)){
            $this->addError('name','权限已经存在');
        }else{
            //创建权限
            $permission=$auth->createPermission($this->name);
            $permission->description=$this->description;
            //保存
            return $auth->add($permission);
        }
        return false;
    }

    //从权限中加载权限名称和描述
    public function loadData(Permission $permission){
        $this->name=$permission->name;
        $this->description=$permission->description;
    }

    //更新权限
    public function updatePermission($name){
        $authManager=\Yii::$app->authManager;
        //获取要修改的权限对象
        $permission=$authManager->getPermission($name);
        //判断修改后的权限名称是否存在
        if($name!=$this->name && $authManager->getPermission($this->name)){
            $this->addError('name','权限已经存在');
        }else{
            //赋值
            $permission->name=$this->name;
            $permission->description=$this->description;
            //更新权限
            return $authManager->update($name,$permission);
        }
        return false;
    }
}