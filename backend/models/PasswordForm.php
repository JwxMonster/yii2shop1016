<?php
/**
 * Created by PhpStorm.
 * User: HXD
 * Date: 2018/3/4
 * Time: 11:11
 */

namespace backend\models;


use yii\base\Model;

class PasswordForm extends Model
{
    public $oldPassword;//旧密码
    public $newPassword;//新密码
    public $rePassword;//确认密码

    public function rules()
    {
        //旧密码要一致  新密码和确认密码一致   新密码和旧密码不能一样   都不能为空
        return [
            //都不能为空
            [['oldPassword','newPassword','rePassword'],'required'],
            //新密码和旧密码不能一样
            ['newPassword','compare','compareAttribute'=>'oldPassword','operator'=>'!=','message'=>'新密码不能和旧密码一样'],
            //确认新密码和新密码一样
            ['rePassword','compare','compareAttribute'=>'newPassword','message'=>'新密码要和确认密码一样'],
            //验证旧密码是否正确 自定义验证规则
            ['oldPassword','validatePassword']
        ];
    }

    public function attributeLabels()
    {
        return [
            'oldPassword'=>'旧密码',
            'newPassword'=>'新密码',
            'rePassword'=>'确认密码'
        ];
    }

    //自定义验证规则 验证旧密码是否正确
    public function validatePassword()
    {
        //处理验证不通过的情况  明文密码和加密过的哈希密码进行对比
        if(!\Yii::$app->security->validatePassword($this->oldPassword,\Yii::$app->user->identity->password_hash)){
            $this->addError('oldPassword','旧密码错误');
        }
    }

}