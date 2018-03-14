<h1>权限添加列表</h1>
<?php
echo \yii\bootstrap\Html::a('▶▶▶返回列表◀◀◀',['rbac/permission-index'],['class'=>'btn btn-success']);
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model,'description')->textarea();
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();