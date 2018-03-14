<h2>权限展示列表</h2>
<?=\yii\bootstrap\Html::a('▶▶▶添加新的权限◀◀◀',['rbac/permission-add'],['class'=>'btn btn-success'])?>
<table class="table table-responsive table-bordered">
    <thead>
    <tr>
        <th>名称</th>
        <th>描述</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($models as $model):?>
        <tr>
            <td><?=$model->name?></td>
            <td><?=$model->description?></td>
            <td><?=\yii\bootstrap\Html::a('修改',['rbac/permission-edit','name'=>$model->name],['class'=>'btn btn-warning btn-xs'])?>
                <?=\yii\bootstrap\Html::a('删除',['rbac/permission-del','name'=>$model->name],['class'=>'btn btn-danger btn-xs'])?></td>
        </tr>
    <?php endforeach;?>
    </tbody>
</table>
<?php
/**
 * @var $this \yii\web\View
 */
$this->registerCssFile('@web/datatables/dataTables.bootstrap.css');
$this->registerJsFile('@web/datatables/jquery.dataTables.min.js',['depends'=>\yii\web\JqueryAsset::className()]);
$this->registerJsFile('@web/datatables/dataTables.bootstrap.js',['depends'=>\yii\web\JqueryAsset::className()]);
$this->registerJs('$(".table").DataTable({
language: {
        "sProcessing":   "处理中...",
	"sLengthMenu":   "显示 _MENU_ 项结果",
	"sZeroRecords":  "没有匹配结果",
	"sInfo":         "显示第 _START_ 至 _END_ 项结果，共 _TOTAL_ 项",
	"sInfoEmpty":    "显示第 0 至 0 项结果，共 0 项",
	"sInfoFiltered": "(由 _MAX_ 项结果过滤)",
	"sInfoPostFix":  "",
	"sSearch":       "搜索:",
	"sUrl":          "",
	"sEmptyTable":     "表中数据为空",
	"sLoadingRecords": "载入中...",
	"sInfoThousands":  ",",
	"oPaginate": {
		"sFirst":    "首页",
		"sPrevious": "上页",
		"sNext":     "下页",
		"sLast":     "末页"
	},
	"oAria": {
		"sSortAscending":  ": 以升序排列此列",
		"sSortDescending": ": 以降序排列此列"
	}
	}
});');
