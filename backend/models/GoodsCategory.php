<?php
/**
 * Created by PhpStorm.
 * User: HXD
 * Date: 2018/2/7
 * Time: 15:11
 */

namespace backend\models;


use yii\db\ActiveRecord;
use creocoder\nestedsets\NestedSetsBehavior;
use yii\helpers\ArrayHelper;


class GoodsCategory extends ActiveRecord
{
    /**
     * This is the model class for table "goods_category".
     *
     * @property int $id
     * @property int $tree 树id
     * @property int $lft 左值
     * @property int $rgt 右值
     * @property int $depth 层级
     * @property string $name 名称
     * @property int $parent_id 上级分类id
     * @property string $intro 简介
     */

    public function rules()
    {
        return [
            [['name','parent_id'],'required'],
            [['tree', 'lft', 'rgt', 'depth', 'parent_id'], 'integer'],
            [['intro'], 'string'],
            [['name'], 'string', 'max' => 50],
            //分类名称不能重复
            ['name','unique'],
        ];
    }
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tree' => 'Tree',
            'lft' => 'Lft',
            'rgt' => 'Rgt',
            'depth' => 'Depth',
            'name' => '名称',
            'parent_id' => '上级分类',
            'intro' => '介绍',
        ];
    }
    //获取顶级分类的ztree数据
    public static function getZNodes(){
        $top=['id'=>0,'parent_id'=>0,'name'=>'顶级分类'];
        $goodsCategories=GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
        return ArrayHelper::merge([$top],$goodsCategories);
    }

    public function behaviors() {
        return [
            'tree' => [
                'class' => NestedSetsBehavior::className(),
                'treeAttribute' => 'tree',
                // 'leftAttribute' => 'lft',
                // 'rightAttribute' => 'rgt',
                // 'depthAttribute' => 'depth',
            ],
        ];
    }

    public function transactions()
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    public static function find()
    {
        return new GoodsCategoryQuery(get_called_class());
    }

}