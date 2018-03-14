<?php

use yii\db\Migration;

/**
 * Handles the creation of table `book`.
 */
class m180122_084030_create_book_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('book', [
            //图书名称,作者(关联其他模型),价格,sn,上架时间,是否上架,图书介绍,图书封面img,创建时间,更新时间
            'id' => $this->primaryKey(),
            'name'=>$this->string(100)->notNull()->comment('图书名'),
            'author'=>$this->string(100)->notNull()->comment('作者'),
            'price'=>$this->decimal(5,2)->defaultValue(0.00)->comment('价格'),
            'total'=>$this->integer(20)->notNull()->comment('库存'),
            'sj_time'=>$this->integer(20)->notNull()->comment('上架时间'),
            'status'=>$this->string(1)->notNull()->comment('是否上架'),
            'intro'=>$this->text()->notNull()->comment('简介'),
            'img'=>$this->string(100)->notNull()->comment('图书封面'),
            'create_time'=>$this->integer(20)->notNull()->comment('创建时间'),
            'update_time'=>$this->integer(20)->notNull()->comment('更新时间')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('book');
    }
}
