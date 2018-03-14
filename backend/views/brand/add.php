<?php
$form=\yii\bootstrap\ActiveForm::begin();
//名称
echo $form->field($model,'name')->textInput();
//排序
echo $form->field($model,'sort')->textInput(['type'=>'number']);
//状态
echo $form->field($model,'is_deleted',['inline'=>true])->radioList(\backend\models\Brand::getStatusOptions());
//图片
echo $form->field($model,'imgFile')->fileInput();
//简介
echo $form->field($model,'intro')->textarea(['rows'=>3]);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();