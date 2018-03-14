<?php
/**
 * Created by PhpStorm.
 * User: HXD
 * Date: 2018/3/3
 * Time: 16:10
 */

namespace backend\models;


use yii\base\Model;

class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe;

    public function rules()
    {
        return [
            [['username','password'],'required'],
            ['rememberMe','boolean']
        ];
    }

    public function attributeLabels()
    {
        return [
          'username'=>'用户名',
          'password'=>'密码',
            'rememberMe'=>'记住我'
        ];
    }

    //登录
    public function login(){
        //根据用户名查找用户
        $admin=Admin::findOne(['username'=>$this->username]);
        //验证用户名和密码
        if($admin){
            if(\Yii::$app->security->validatePassword($this->password,$admin->password_hash)){
                //登录和自动登录
                $log=$this->rememberMe ? 7*24*3600 : 0 ;
                //保存用户最后登录的时间和IP
                Admin::updateAll(['last_login_time'=>time(),'last_login_ip'=>ip2long(\Yii::$app->request->userIP)],['id'=>$admin->id]);
                \Yii::$app->user->login($admin,$log);
                return true;
            }else{
                $this->addError('password','用户密码错误');
            }
        }else{
            $this->addError('username','用户名不存在');
        }
        return false;
    }

}