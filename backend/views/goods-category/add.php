<?php
$form=\yii\bootstrap\ActiveForm::begin();
//商品分类名称
echo $form->field($model,'name')->textInput();
//父ID
echo $form->field($model,'parent_id')->hiddenInput();
//==================zTree===================
echo '<div>
    <ul id="treeDemo" class="ztree"></ul>
</div>';

//===================zTree========================
//简介
echo $form->field($model,'intro')->textarea();
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();

//注册ztree的静态资源和JS
/*
 * @var $this \yii\web\View
 */
//加载css文件
$this->registerCssFile('@web/zTree/css/zTreeStyle/zTreeStyle.css');
//加载js文件(需要在jquery后面加载,解决依赖问题，设置depends参数)
$this->registerJsFile('@web/zTree/js/jquery.ztree.core.js',['depends'=>\yii\web\JqueryAsset::className()]);
//获取数据
$goodsCategories=json_encode(\backend\models\GoodsCategory::getZNodes());
$nodeId = $model->parent_id;
$this->registerJS(new \yii\web\JsExpression(
    <<<JS
    var zTreeObj;
        // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
        var setting = {
            data: {
                simpleData: {
                    enable: true,
                    idKey: "id",
                    pIdKey: "parent_id",
                    rootPId: 0
                }
            },
                callback: {//定义鼠标点击事件回调函数
                onClick: function(event, treeId, treeNode) {
                  console.log(treeNode);
                  //获取当前点击节点的ID，放入parent_id的框中
                  $("#goodscategory-parent_id").val(treeNode.id);
                }
	}
        };
        // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
        var zNodes ={$goodsCategories} ;
        
            zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
            //展开全部节点
            zTreeObj.expandAll(true);
            
        //获取节点
        var node = zTreeObj.getNodeByParam("id", "{$nodeId}", null);
        //选中节点
        zTreeObj.selectNode(node);
        //触发选中事件
        

JS

));