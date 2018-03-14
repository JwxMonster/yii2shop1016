<?php
/* @var $this yii\web\View */
?>
<h1>Admin展示列表</h1>
<?=\yii\bootstrap\Html::a('▶▶▶添加新的用户◀◀◀',['admin/add'],['class'=>'btn btn-success'])?>
<form action="" method="get">
    <div class="input-group col-md-3 pull-right" style="margin-top:0px positon:relative">
        <input type="text" name="keywords" class="form-control" placeholder="请输入查找内容"  / >
        <span class="input-group-btn">
               <button class="btn btn-info btn-search">搜索</button>
            </span>
    </div>
</form>
<table class="table table-bordered table-hover">
    <tr>
        <th>ID</th>
        <th>用户名</th>
        <th>密码</th>
        <th>邮箱</th>
        <th>状态</th>
        <th>创建时间</th>
        <th>修改时间</th>
        <th>最后登录时间</th>
        <th>最后登录IP</th>
        <th>操作</th>
    </tr>
    <?php foreach($models as $model):?>
        <tr>
            <td><?=$model->id?></td>
            <td><?=$model->username?></td>
            <td><?=substr($model->password_hash,0,10).'......'?></td>
            <td><?=$model->email?></td>
            <td><?=\backend\models\Admin::$status_options[$model->status]?></td>
            <td><?=date('Y-m-d H:i:s',$model->create_at)?></td>
            <td><?=$model->updated_at==null?'没有修改过':date('Y-m-d H:i:s',$model->updated_at)?></td>
            <td><?=$model->last_login_time==null?'没有登录过':date('Y-m-d H:i:s',$model->last_login_time)?></td>
            <td><?=$model->last_login_ip==null ? '无' : $model->last_login_ip?></td>
            <td>
                <?=\yii\bootstrap\Html::a('编辑',['admin/edit','id'=>$model->id],['class'=>'btn btn-success'])?>
                <?=\yii\bootstrap\Html::a('删除',['admin/del','id'=>$model->id],['class'=>'btn btn-danger'])?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<?php
echo \yii\widgets\LinkPager::widget([
'pagination'=>$pager,
'nextPageLabel'=>'下一页',
'prevPageLabel'=>'上一页',
'firstPageLabel'=>'首页',
'lastPageLabel'=>'尾页'
]);
