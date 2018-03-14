<h1><?=$model->name?></h1>
<?=\yii\bootstrap\Carousel::widget([
    'items' => $model->getPics()
]);?>
<div class="container" style="width: 600">
    <?=$model->goodsIntro->content?>
</div>
