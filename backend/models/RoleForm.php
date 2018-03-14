<?php
/**
 * Created by PhpStorm.
 * User: HXD
 * Date: 2018/3/5
 * Time: 16:38
 */

namespace backend\models;


use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\rbac\Role;

class RoleForm extends Model
{
    public $name;
    public $description;
    public $permissions=[];//角色的权限

    public function rules()
    {
        return [
            [['name','description'],'required'],
            ['permissions','safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'name'=>'名称',
            'description'=>'描述',
            'permissions'=>'权限'
        ];
    }

    //获取权限选项
    public static function getPermissionOptions(){
        $authManager=\Yii::$app->authManager;
        return ArrayHelper::map($authManager->getPermissions(),'name','description');
    }

    //添加角色
    public function addRole(){
        $authManager=\Yii::$app->authManager;
        //判断角色是否存在
        if($authManager->getRole($this->name)){
            $this->addError('name','角色已经存在');
        }else{
            $role=$authManager->createRole($this->name);
            $role->description=$this->description;
            if($authManager->add($role)){//保存到数据表
                //关联该用户角色
                foreach ($this->permissions as $permissionName){
                    $permission=$authManager->getPermission($permissionName);
                    if($permission)$authManager->addChild($role,$permission);
                }
                return true;
            }
        }
        return false;
    }

    //获取该角色对应的所有权限
    public function loadData(Role $role){
        $this->name=$role->name;
        $this->description=$role->description;
        //获取该角色对应的权限
        $permissions=\Yii::$app->authManager->getPermissionsByRole($role->name);
        foreach ($permissions as $permission){
            $this->permissions[]=$permission->name;
        }
    }

    //更新角色
    public function updateRole($name){
        $authManager=\Yii::$app->authManager;
        $role=$authManager->getRole($name);
        //给角色赋值
        $role->name=$this->name;
        $role->description=$this->description;
        //检查角色名称是否已经存在
        if($name!=$this->name && $authManager->getRole($this->name)){
            $this->addError('name','角色名称已经存在');
        }else{
            if($authManager->update($name,$role)){
                //去掉所有与该角色相关的权限
                $authManager->removeChildren($role);
                //关联该角色的权限
                foreach($this->permissions as $permissionName){
                    $permission=$authManager->getPermission($permissionName);
                    if($permission)$authManager->addChild($role,$permission);
                }
                return true;
            }
        }
        return false;
    }

}