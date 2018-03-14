<?php
/**
 * Created by PhpStorm.
 * User: HXD
 * Date: 2018/2/4
 * Time: 14:10
 */

namespace backend\models;

use yii\db\ActiveRecord;

class Brand extends ActiveRecord
{
    //保存上传的文件
    public $imgFile;

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
            [['name','intro','sort','is_deleted'],'required','message'=>'{attribute}必填'],
            ['imgFile','file','extensions'=>['jpg','png','gif']]
        ];
    }
    public function attributeLabels(){
        return [
            'name'=>'名称',
            'intro'=>'简介',
            'sort'=>'排序',
            'imgFile'=>'品牌图片',
            'is_deleted'=>'状态'
        ];
    }

}