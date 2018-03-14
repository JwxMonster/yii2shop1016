<?php
$url=\yii\helpers\Url::toRoute(['get-region']);
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model, 'province')->widget(\chenkby\region\Region::className(),[
    'model'=>$model,
    'url'=>$url,
    'province'=>[
        'attribute'=>'province',
        'items'=>Region::getRegion(),
        'options'=>['class'=>'form-control form-control-inline','prompt'=>'选择省份']
    ],
    'city'=>[
        'attribute'=>'city',
        'items'=>Region::getRegion($model['province']),
        'options'=>['class'=>'form-control form-control-inline','prompt'=>'选择城市']
    ],
    'district'=>[
        'attribute'=>'district',
        'items'=>Region::getRegion($model['city']),
        'options'=>['class'=>'form-control form-control-inline','prompt'=>'选择县/区']
    ]
]);

\yii\bootstrap\ActiveForm::end();