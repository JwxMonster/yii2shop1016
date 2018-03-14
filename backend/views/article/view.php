<?=\yii\bootstrap\Html::a('▶▶▶返回文章列表◀◀◀',['article/index'],['class'=>'btn btn-success'])?>
<table class="table">
<tr>
    <th>
        <h3 class="text-left">文章标题</h3>
        <h1 class="text-center"><?=$model->name?></h1>
    </th>
</tr>
    <tr>
        <th>
            <h3 class="text-left">文章内容</h3>
            <?=$model->detail->content?>
        </th>
    </tr>
    <tr>
        <th>
            <h3 class="text-left">文章图片</h3>
            <?=\yii\bootstrap\Html::img($model->logo,['height'=>300])?>
        </th>
    </tr>
</table>