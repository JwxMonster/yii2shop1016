<?php
/**
 * Created by PhpStorm.
 * User: HXD
 * Date: 2018/2/7
 * Time: 14:45
 */

namespace backend\controllers;


use backend\models\GoodsCategory;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use backend\filters\RbacFilter;

class GoodsCategoryController extends Controller
{
    //商品分类展示
    public function actionIndex(){
        $models=GoodsCategory::find()->orderBy("tree ASC,lft ASC")->asArray()->all();
        return $this->render('index',['models'=>$models]);
    }
    //商品分类添加
    public function actionAdd(){
        $model=new GoodsCategory();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //判断顶级分类还是非顶级分类（子分类）
            if($model->parent_id){
                //非顶级分类
                $parent=GoodsCategory::findOne(['id'=>$model->parent_id]);
                $model->prependTo($parent);
            }else{
                //顶级分类
                $model->makeRoot();
            }
                $model->save();
                \Yii::$app->session->setFlash('添加成功','success');
                //$this->redirect(['index']);
            return $this->refresh();
        }
        return $this->render('add',['model'=>$model]);
    }
    //修改
    public function actionEdit($id){
        $model=GoodsCategory::findOne(['id'=>$id]);
        if($model==null){
            throw new NotFoundHttpException('该分类不存在');
        }
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //判断是否是一级分类
            if($model->parent_id){
                $parent=GoodsCategory::findOne(['id'=>$model->parent_id]);
                if($parent){
                    $model->prependTo($parent);
                }else{
                    throw new HttpException(404,'上级分类不存在');
                }
            }else{
                //顶级分类改为顶级分类时会报错
                if($model->oldAttributes['parent_id']==0){
                    //save方法只保存名称之类的
                    $model->save();
                }else{
                    $model->makeRoot();
                }
            }
            \Yii::$app->session->setFlash('修改成功','success');
            $this->redirect(['index']);
        }
        return $this->render('add',['model'=>$model]);
    }
    //删除
    public function actionDel($id){
        $model = GoodsCategory::findOne(['id'=>$id]);
        if($model==null){
            throw new NotFoundHttpException('商品分类不存在');
        }
        if(!$model->isLeaf()){//判断是否是叶子节点，非叶子节点说明有子分类
            throw new ForbiddenHttpException('该分类下有子分类，无法删除');
        }
        $model->deleteWithChildren();
        \Yii::$app->session->setFlash('success','删除成功');
        return $this->redirect(['index']);
    }

    //测试Ztree
    public function actionZtree(){
        //不加载布局文件
        //$this->layout=false;
        //将对象转换成数组asArray
        $goodsCategories=GoodsCategory::find()->select(['id','parent_id','name'])->asArray()->all();
        //或
        return $this->renderPartial('ztree',['goodsCategories'=>$goodsCategories]);
    }

    //测试根节点
    public function actionTest(){
        //创建一级分类   顶级分类
/*        $model = new GoodsCategory(['name' => '家用电器']);
        $model->parent_id=0;*/
        //创建子分类
        //查找父分类
        $parent=GoodsCategory::findOne(['id'=>1]);
        $child = new GoodsCategory(['name' => '大家电']);
        $child->parent_id=1;
        $child->prependTo($parent);
        //$model->makeRoot();
        echo '创建成功';
    }
    public function behaviors()
    {
        return [
            'rbac'=>[
                'class'=>RbacFilter::className(),
            ]
        ];
    }

}