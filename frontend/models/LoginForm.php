<?php
/**
 * Created by PhpStorm.
 * User: HXD
 * Date: 2018/3/8
 * Time: 18:22
 */

namespace frontend\models;


use yii\base\Model;

class LoginForm extends Model
{

    public $username;
    public $password;
    public $code;
    public $rememberMe;//记住我

    public function rules()
    {
        return [
            [['username','password'],'required'],
            ['rememberMe','string'],
            ['code','captcha','captchaAction'=>'member/captcha'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username'=>'用户名',
            'password'=>'密码',
            'rememberMe'=>'记住我',
            'code'=>'验证码'
        ];
    }

    public function login(){
        //通过用户名查找数据
        $user=Member::findOne(['username'=>$this->username]);
        //判断是否存在用户
        if($user){
            //存在用户，将传过来的密码与数据库密码作对比
            if(\Yii::$app->security->validatePassword($this->password,$user->password_hash)){
                //密码正确，登录，保存到session，
                \Yii::$app->user->login($user,$this->rememberMe ? 3600: 0);
                $user->last_login_time=time();
                //获取当前登录ip
                $ip=\Yii::$app->request->getUserIP();
                $user->last_login_ip=$ip;
                $user->save(false);
                return true;
            }else{
                //密码错误
                $this->addError('password_hash','密码错误');
            }
        }else{//用户名不存在
            //用户名不存在
            $this->addError('username','用户不存在');
        }
        return false;
    }

}