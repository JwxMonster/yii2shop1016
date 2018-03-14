<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'label')->textInput();
echo $form->field($model,'parent_id')->dropDownList(\backend\models\Menu::getMenuOptions(),['prompt'=>'请选择一级菜单']);
echo $form->field($model,'url')->dropDownList(\backend\models\Menu::getUrlOptions(),['prompt'=>'请选择路由']);
echo $form->field($model,'sort')->textInput();
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();