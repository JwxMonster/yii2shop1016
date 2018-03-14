<h1>菜单列表</h1>
<?=\yii\bootstrap\Html::a('▶▶▶添加新的菜单◀◀◀',['menu/add'],['class'=>'btn btn-success'])?>
<table class="table table-hover">
    <tr>
        <th>名称</th>
        <th>路由</th>
        <th>排序</th>
        <th>操作</th>
    </tr>
    <?php foreach($models as $model):?>
        <tr>
            <td><?=$model->label?></td>
            <td><?=$model->url?></td>
            <td><?=$model->sort?></td>
            <td>
                <?=\yii\bootstrap\Html::a('编辑',['menu/edit','id'=>$model->id],['class'=>'btn btn-success'])?>
                <?=\yii\bootstrap\Html::a('删除',['menu/del','id'=>$model->id],['class'=>'btn btn-danger'])?>
            </td>
        </tr>
        <?php foreach($model->children as $child):?>
            <tr>
                <td>——<?=$child->label?></td>
                <td><?=$child->url?></td>
                <td><?=$child->sort?></td>
                <td>
                    <?=\yii\bootstrap\Html::a('编辑',['menu/edit','id'=>$child->id],['class'=>'btn btn-success'])?>
                    <?=\yii\bootstrap\Html::a('删除',['menu/del','id'=>$child->id],['class'=>'btn btn-danger'])?>
                </td>
            </tr>
        <?php endforeach;?>
    <?php endforeach;?>
</table>