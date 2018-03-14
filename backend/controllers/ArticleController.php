<?php
/**
 * 文章
 */

namespace backend\controllers;


use backend\models\Article;
use backend\models\ArticleDetail;
use yii\data\Pagination;
use yii\web\Controller;
use flyok666\uploadifive\UploadAction;
use flyok666\qiniu\Qiniu;
use backend\filters\RbacFilter;

class ArticleController extends Controller
{
    //public $enableCsrfValidation = false;//关闭csrf验证
    //列表 搜索分页
    public function actionIndex($keywords=''){
        $query=Article::find()->where(['and','status>-1',"name like '%{$keywords}%' "]);
        $page=new Pagination([
            'totalCount'=>$query->count(),
            'defaultPageSize'=>2
        ]);
        $articles=$query->limit($page->limit)->offset($page->offset)->all();
        return $this->render('index',['articles'=>$articles,'page'=>$page]);

    }
    //添加
    public function actionAdd()
    {
        $article = new Article();
        $article_detail = new ArticleDetail();
        if($article->load(\Yii::$app->request->post())
            && $article_detail->load(\Yii::$app->request->post())
            && $article->validate()
            && $article_detail->validate()){
            $article->create_time=time();
            $article->save();
            $article_detail->article_id = $article->id;
            $article_detail->save();
            \Yii::$app->session->setFlash('success','文章添加成功');
            return $this->redirect(['index']);
        }
        return $this->render('add',['article'=>$article,'article_detail'=>$article_detail]);
    }

    //修改
    public function actionEdit($id){
        $article=Article::findOne(['id'=>$id]);
        $article_detail=$article->detail;
        if($article->load(\Yii::$app->request->post())
            && $article_detail->load(\Yii::$app->request->post())
            && $article->validate()
            && $article_detail->validate()){
            $article->save();
            $article_detail->save();
            \Yii::$app->session->setFlash('修改成功','success');
            return $this->redirect('index');
        }
        return $this->render('add',['article'=>$article,'article_detail'=>$article_detail]);
    }

    //视图
    public function actionView($id){
        $model=Article::findOne(['id'=>$id]);
        return $this->render('view',['model'=>$model]);
    }

    public function actions() {
        return [
            's-upload' => [
                'class' => UploadAction::className(),
                'basePath' => '@webroot/upload',
                'baseUrl' => '@web/upload',
                'enableCsrf' => true, // default
                'postFieldName' => 'Filedata', // default
                //BEGIN METHOD
                //'format' => [$this, 'methodName'],
                //END METHOD
                //BEGIN CLOSURE BY-HASH
                'overwriteIfExist' => true,//如果文件已存在，是否覆盖
                /*'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filename = sha1_file($action->uploadfile->tempName);
                    return "{$filename}.{$fileext}";
                },*/
                //END CLOSURE BY-HASH
                //BEGIN CLOSURE BY TIME
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filehash = sha1(uniqid() . time());//生成哈希的字符串
                    $p1 = substr($filehash, 0, 2);
                    $p2 = substr($filehash, 2, 2);
                    return "{$p1}/{$p2}/{$filehash}.{$fileext}";
                },
                //END CLOSURE BY TIME
                'validateOptions' => [
                    'extensions' => ['jpg', 'png'],
                    'maxSize' => 1 * 1024 * 1024, //file size
                ],
                'beforeValidate' => function (UploadAction $action) {
                    //throw new Exception('test error');
                },
                'afterValidate' => function (UploadAction $action) {},
                'beforeSave' => function (UploadAction $action) {},
                'afterSave' => function (UploadAction $action) {
                    //$action->output['fileUrl'] = $action->getWebUrl();//输出图片路径
/*                  $action->getFilename(); // "image/yyyymmddtimerand.jpg"
                    $action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
                    $action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"//绝对路径
                    */
                    //将图片上传到服务器上
/*                    $config = [
                        'accessKey'=>'gxF-mFVg366hY9gVhs5NBX7wNiZyDrQSaIU2XyzI',
                        'secretKey'=>'yu4EIGPSUgW5TAjhVBnS4FgjARcfDv0wZuoFzgIu',
                        'domain'=>'p3rfhof78.bkt.clouddn.com',
                        'bucket'=>'yii2shop',
                        'area'=>Qiniu::AREA_HUADONG//华东
                    ];*/
                    $qiniu = new Qiniu(\Yii::$app->params['qiniuyun']);
                    $key = $action->getWebUrl();
                    $file=$action->getSavePath();
                    //上传文件到七牛云，同时制定一个key，文件名称
                    $qiniu->uploadFile($file,$key);
                    //获取上传到七牛云的url地址
                    $url = $qiniu->getLink($key);
                    $action->output['fileUrl'] = $url;//输出图片路径
                    //var_dump(json_encode($url));exit;
                },
            ],
        ];
    }

    //测试七牛云
    public function actionQiniu(){
        $config = [
            'accessKey'=>'gxF-mFVg366hY9gVhs5NBX7wNiZyDrQSaIU2XyzI',
            'secretKey'=>'yu4EIGPSUgW5TAjhVBnS4FgjARcfDv0wZuoFzgIu',
            'domain'=>'p3rfhof78.bkt.clouddn.com',
            'bucket'=>'yii2shop',
            'area'=>Qiniu::AREA_HUADONG
        ];
        $qiniu = new Qiniu($config);
        $key = time();
        //上传文件到七牛云，同时制定一个key，文件名称
        $qiniu->uploadFile($_FILES['tmp_name'],$key);
        //获取上传到七牛云的url地址
        $url = $qiniu->getLink($key);
/*        $qiniu = new Qiniu($config);
        $file=\Yii::getAlias('@webroot/upload/1.jpg');
        $key = '1.jpg';
        //上传文件到七牛云，同时制定一个key，文件名称
        $qiniu->uploadFile($file,$key);
        //获取上传到七牛云的url地址
        $url = $qiniu->getLink($key);
        var_dump($url);*/
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