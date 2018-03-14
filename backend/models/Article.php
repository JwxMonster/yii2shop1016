<?php
/**
 * Created by PhpStorm.
 * User: HXD
 * Date: 2018/2/5
 * Time: 9:26
 */

namespace backend\models;


use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class Article extends ActiveRecord
{
    public static function tableName(){
        return 'article';
    }
    //处理状态
    public static $status_options=[1=>'正常',0=>'删除'];

    public function rules(){
//name	varchar(50)	名称      intro	text简介          article_category_id int(11)	文章分类id
//sort	int(11)	排序          is_deleted	int(2)	状态(0正常 1删除)         create_time	int(11)	创建时间
        return[
            [['name','intro','article_category_id','sort','status'],'required','message'=>'{attribute}必填'],
            [['article_category_id','sort','status','create_time'],'integer'],
            [['intro'],'string'],
            [['name'],'string','max'=>50],
            ['logo','string','max'=>255]
        ];
    }
    public function attributeLabels(){
        return[
            'name'=>'名称',
            'intro'=>'简介',
            'article_category_id'=>'文章分类',
            'sort'=>'排序',
            'status'=>'状态',
            'create_time'=>'创建时间',
            'logo'=>'文章LOGO'
        ];
    }

    public static function getCategoryOptions()
    {
        return ArrayHelper::map(ArticleCategory::find()->where(['status'=>1])->asArray()->all(),'id','name');
    }
    public function getCategory()
    {
        return $this->hasOne(ArticleCategory::className(),['id'=>'article_category_id']);
    }
    public function getDetail()
    {
        return $this->hasOne(ArticleDetail::className(),['article_id'=>'id']);
    }

}