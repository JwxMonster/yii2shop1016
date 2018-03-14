<?php
/**
 * Created by PhpStorm.
 * User: HXD
 * Date: 2018/2/4
 * Time: 18:32
 */

namespace backend\models;


use yii\db\ActiveRecord;

class ArticleCategory extends ActiveRecord
{
    //处理状态
    public static function getStatusOptions($hidden_del=true){
        $options=[
            -1=>'删除',0=>'隐藏',1=>'正常'
        ];
        if($hidden_del){
            unset($options['-1']);
        }
        return $options;
    }

    public function rules(){
        return[
            [['name','intro','sort','status'],'required','message'=>'{attribute}必填']
        ];
    }

    public function attributeLabels(){
        return[
            'name'=>'名称',
            'intro'=>'简介',
            'sort'=>'排序',
            'status'=>'状态'
        ];
    }

}