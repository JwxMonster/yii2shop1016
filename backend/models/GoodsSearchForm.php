<?php
/**
 * Created by PhpStorm.
 * User: HXD
 * Date: 2018/2/14
 * Time: 17:13
 */

namespace backend\models;


use yii\base\Model;
use yii\db\ActiveQuery;

class GoodsSearchForm extends Model
{
    public $name;
    public $sn;
    public $minPrice;//最小价格
    public $maxPrice;//最大价格

    public function rules()
    {
        return[
            ['name','string','max'=>50],
            ['sn','string'],
            [['minPrice','maxPrice'],'double']
        ];
    }

    public function search(ActiveQuery $query){
        //加载表单提交的数据
        $this->load(\Yii::$app->request->get());
        if($this->name){
            $query->andWhere(['like','name',$this->name]);
        }
        if($this->sn){
            $query->andWhere(['like','sn',$this->sn]);
        }
        if($this->maxPrice){
            $query->andWhere(['<=','maxPrice',$this->maxPrice]);
        }
        if($this->minPrice){
            $query->andWhere(['>=','minPrice',$this->minPrice]);
        }
    }

}