<?php
use yii\web\JsExpression;

$form=\yii\bootstrap\ActiveForm::begin();
//name	varchar(50)	名称
echo $form->field($article,'name')->textInput();
//intro	text	简介
echo $form->field($article,'intro')->textarea(['rows'=>3]);
//article_category_id	int(11)	文章分类id
echo $form->field($article,'article_category_id')->dropDownList(\backend\models\Article::getCategoryOptions(),['prompt'=>'请选择分类']);
//sort	int(11)	排序
echo $form->field($article,'sort')->textInput(['type'=>'number']);
//is_deleted	int(2)	状态(0正常 1删除)
echo $form->field($article,'status')->radioList(\backend\models\Article::$status_options);

//图片
echo $form->field($article,'logo')->hiddenInput();
//外部TAG
echo \yii\bootstrap\Html::fileInput('test', NULL, ['id' => 'test']);
echo \flyok666\uploadifive\Uploadifive::widget([
    'url' => yii\helpers\Url::to(['s-upload']),
    'id' => 'test',
    'csrf' => true,
    'renderTag' => false,
    'jsOptions' => [
        'formData'=>['someKey' => 'someValue'],
        'width' => 120,
        'height' => 40,
        'onError' => new JsExpression(<<<EOF
function(file, errorCode, errorMsg, errorString) {
    console.log('The file ' + file.name + ' could not be uploaded: ' + errorString + errorCode + errorMsg);
}
EOF
        ),
        'onUploadComplete' => new JsExpression(<<<EOF
function(file, data, response) {
    data = JSON.parse(data);
    if (data.error) {
        console.log(data.msg);
    } else {
        console.log(data.fileUrl);
        var absolute='http://' + data.fileUrl
        //将上传文件的地址写入logo隐藏域中
        $("#article-logo").val(absolute);
        //图片回显
        $("#img").attr("src",absolute);
    }
}
EOF
        ),
    ]
]);
echo \yii\bootstrap\Html::img($article->logo,['id'=>'img','height'=>100]);
echo $form->field($article_detail,'content')->textarea(['rows'=>5]);
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();
