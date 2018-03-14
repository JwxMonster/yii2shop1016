<?php
/**
 * Created by PhpStorm.
 * User: HXD
 * Date: 2018/3/12
 * Time: 16:02
 */

namespace frontend\models;


use yii\db\ActiveRecord;

class Cart extends ActiveRecord
{
    public function rules()
    {
        return [
            [['goods_id','member_id','amount'],'required']
        ];
    }

}