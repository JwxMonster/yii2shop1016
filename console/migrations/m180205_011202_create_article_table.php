<?php

use yii\db\Migration;

/**
 * Handles the creation of table `article`.
 */
class m180205_011202_create_article_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('article', [
            'id' => $this->primaryKey(),
            //name	varchar(50)	名称
            'name'=>$this->string(50)->notNull()->comment('名称'),
            //intro	text	简介
            'intro'=>$this->text()->notNull()->comment('简介'),
            //article_category_id	int(11)	文章分类id
            'article_category_id'=>$this->integer(11)->comment('文章分类ID'),
            //sort	int(11)	排序
            'sort'=>$this->integer(11)->comment('排序'),
            //is_deleted	int(2)	状态(0正常 1删除)
            'status'=>$this->integer(2)->comment('状态'),
            //create_time	int(11)	创建时间
            'create_time'=>$this->integer(11)->comment('创建时间'),
            //logo
            'logo'=>$this->string(255)->comment('文章图片')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('article');
    }
}
