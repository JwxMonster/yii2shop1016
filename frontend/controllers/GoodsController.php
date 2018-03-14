<?php
/**
 * Created by PhpStorm.
 * User: HXD
 * Date: 2018/3/8
 * Time: 18:52
 */

namespace frontend\controllers;


use frontend\models\Goods;
use frontend\models\GoodsCategory;
use frontend\models\GoodsGallery;
use frontend\models\GoodsIntro;
use yii\data\Pagination;
use yii\web\Controller;

class GoodsController extends Controller
{
    public $layout=false;

    //首页展示
    public function actionIndex(){
        $categorys=GoodsCategory::find()->where(['parent_id'=>0])->all();
        return $this->render('index',['categorys'=>$categorys]);
    }

    //商品列表页
    public function actionList($pid){
        $id=GoodsCategory::getId($pid);
        $query=Goods::find()->where(['in','goods_category_id',$id]);
        //总条数
        $total=$query->count();
        $pageSize=3;
        //工具条
        $pager=new Pagination([
            'totalCount'=>$total,
            'defaultPageSize'=>$pageSize,
        ]);
        //根据条件查询
        $goods=$query->limit($pager->limit)->offset($pager->offset)->all();
        $categorys=GoodsCategory::find()->where(['parent_id'=>0])->all();
        //显示左边的导航栏
        $categoryNows=GoodsCategory::find()->where(['id'=>$pid])->one();
        return $this->render('list',['pager'=>$pager,'goods'=>$goods,'categorys'=>$categorys,'categoryNows'=>$categoryNows]);
    }

    //商品详情页
    public function actionShow($id=1){
        $goods=Goods::findOne(['id'=>$id]);
        $gallerys=GoodsGallery::find()->where(['goods_id'=>$id])->all();
        $intro=GoodsIntro::findOne(['goods_id'=>$id]);
        return $this->render('show',['goods'=>$goods,'gallerys'=>$gallerys,'intro'=>$intro]);
    }

}