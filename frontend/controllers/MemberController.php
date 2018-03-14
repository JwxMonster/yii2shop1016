<?php
/**
 * Created by PhpStorm.
 * User: HXD
 * Date: 2018/3/8
 * Time: 14:24
 */

namespace frontend\controllers;

use frontend\models\Address;
use frontend\models\Locations;
use frontend\models\LoginForm;
use frontend\models\Member;

use frontend\models\SmsDemo;
use yii\captcha\CaptchaAction;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Request;

class MemberController extends Controller
{
    //用户注册
    public function actionRegister(){
        $model=new Member();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            $model->save(false);
            \Yii::$app->session->setFlash('注册成功');
            return $this->redirect(['member/login']);
        }
        return $this->render('register',['model'=>$model]);
    }

    //用户登录
    public function actionLogin(){
        $model=new LoginForm();
        if($model->load(\Yii::$app->request->post()) && $model->validate() && $model->login()){
            \Yii::$app->session->setFlash('success','登录成功');
            return $this->redirect(['goods/index']);
        }
        return $this->render('login',['model'=>$model]);
    }

    //退出
    public function actionLogout(){
        \Yii::$app->user->logout();
        return $this->redirect(['goods/index']);
    }

    //收货地址管理
    public function actionAddress(){
        $model=new Address();
        $user_id=\Yii::$app->user->getId();
        //var_dump($user_id);exit;
        $address=$model->find()->where(['user_id'=>$user_id])->all();
        $request=new Request();
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                $model->save();
                return $this->redirect(['member/address']);
            }else{
                print_r($model->getErrors());exit;
            }
        }
        return $this->render('address',['model'=>$model,'address'=>$address]);
    }

    //删除地址
    public function actionDelAddress($id){
        $model=Address::findOne(['id'=>$id]);
        if($model==null){
            throw new NotFoundHttpException('地址不存在');
        }
        $model->delete();
        return $this->redirect(['member/address']);
    }

    //修改地址
    public function actionEditAddress($id){
        //实例化模型
        $model = new Address();
        $user_id=\Yii::$app->user->identity->id;
        $address =$model->find()->where(['user_id'=>$user_id])->all();
        $model=Address::findOne(['id'=>$id]);
        $request = new Request();
        //判断提条方式
        if($request->isPost){
            //加载数据
            $model->load($request->post());
            if( $model->validate()){
                $model->save();
                return $this->redirect(['member/address']);
            }else{//验证失败，打印错误信息
                print_r($model->getErrors());exit;
            }
        }
        //调用视图，分配数据
        return $this->render('address',['model'=>$model,'address'=>$address]);
    }

    //设置默认地址
    public function actionChgStatus($id){
        $model=Address::findOne(['id'=>$id]);
        if($model->status==0){
            $model->status=1;
        }
        $model->update(false,['status']);
        return $this->redirect(['member/address']);
    }

    //得到三级联动城市
    public function actionLocations($id){
        $model=new Locations();
        return $model->getProvince($id);
    }

    //测试发送短信
    public function actionSms($tel)
    {
        $dcode=rand(1000,9999);
        //$tel=18582595554;
        $res = \Yii::$app->sms->setPhoneNumbers($tel)->setTemplateParam(['code'=>$dcode])->send();
        //将短信验证码保存到session。
        \Yii::$app->session->set('code_'.$tel,$dcode);
    }

    //测试redis操作
    public function actionRedis(){
        $redis=new \Redis();
        //连接redis
        $redis->connect('127.0.0.1');
        //写入数据
        $redis->set('name','张三');
        //获取数据
        //$redis->get('name');
        echo 'OK';
    }
    //验证码
     public function actions()
    {
        return [
            'captcha'=>[
                'class'=>CaptchaAction::className(),
                //验证码的长度
                'minLength'=>4,
                'maxLength'=>4,
            ]
        ];
    }

}