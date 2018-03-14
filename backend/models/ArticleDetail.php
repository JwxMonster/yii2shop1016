<?php
/**
 * Created by PhpStorm.
 * User: HXD
 * Date: 2018/2/5
 * Time: 9:54
 */

namespace backend\models;


use yii\db\ActiveRecord;

class ArticleDetail extends ActiveRecord
{
    public function rules()
    {
        return [
            [[ 'content'], 'required'],
            [['article_id'], 'integer'],
            [['content'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'article_id' => '文章id',
            'content' => '内容',
        ];
    }

}