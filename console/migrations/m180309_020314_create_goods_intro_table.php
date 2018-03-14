<?php

use yii\db\Migration;

/**
 * Handles the creation of table `goods_intro`.
 */
class m180309_020314_create_goods_intro_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('goods_intro', [
            'goods_id'=>$this->integer()->comment('商品ID'),
//            content	text	商品描述
            'content'=>$this->text()->comment('商品描述'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('goods_intro');
    }
}
