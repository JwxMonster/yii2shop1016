<h1>品牌列表</h1>
<?php
echo yii\bootstrap\Html::a('添加品牌',['brand/add'],['class'=>'btn btn-success']);
?>
<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <th>品牌名称</th>
        <th>排序</th>
        <th>状态</th>
        <th>图片</th>
        <th>简介</th>
        <th>操作</th>
    </tr>
    <?php foreach ($brands as $brand):?>
    <tr>
        <td><?=$brand->id?></td>
        <td><?=$brand->name?></td>
        <td><?=$brand->sort?></td>
        <td><?=$brand->is_deleted==1?'正常':'隐藏'?></td>
        <td><?=\yii\bootstrap\Html::img($brand->logo,['height'=>50])?></td>
        <td><?=$brand->intro?></td>
        <td>
            <?=\yii\bootstrap\Html::a('修改',['brand/edit','id'=>$brand->id],['class'=>'btn btn-info'])?>
            <?=\yii\bootstrap\Html::a('删除',['brand/del','id'=>$brand->id],['class'=>'btn btn-danger'])?>
        </td>
    </tr>
    <?php endforeach;?>
</table>
<?php
//分页工具条
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$page,
    'nextPageLabel'=>'下一页',
    'prevPageLabel'=>'上一页',
    'firstPageLabel'=>'首页',
    'lastPageLabel'=>'尾页'
]);

