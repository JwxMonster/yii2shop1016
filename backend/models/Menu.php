<?php
/**
 * Created by PhpStorm.
 * User: HXD
 * Date: 2018/3/7
 * Time: 19:41
 */

namespace backend\models;


use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class Menu extends ActiveRecord
{
    public function rules()
    {
        return [
            [['label','sort','parent_id'],'required'],
            [['sort','parent_id'],'integer'],
            [['label'],'string','max'=>20],
            [['url'],'string','max'=>255]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'label'=>'名称',
            'sort'=>'排序',
            'url'=>'路径/路由',
            'parent_id'=>'上级菜单'
        ];
    }

    //获取所有的一级菜单
    public static function getMenuOptions(){
        return ArrayHelper::merge([0=>'顶级菜单'],ArrayHelper::map(self::find()->where(['parent_id'=>0])->asArray()->all(),'id','label'));
    }

    //获取地址选项
    public static function getUrlOptions(){
        return ArrayHelper::map(\Yii::$app->authManager->getPermissions(),'name','name');
    }

    //获取子菜单
    public function getChildren(){
        return $this->hasMany(self::className(),['parent_id'=>'id']);
    }



}