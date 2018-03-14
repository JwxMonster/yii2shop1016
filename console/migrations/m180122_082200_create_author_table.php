<?php

use yii\db\Migration;

/**
 * Handles the creation of table `author`.
 */
class m180122_082200_create_author_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('author', [
            //作者表(表名:author;字段:id name age sex head(头像) birthday(生日)),创建时间
            'id' => $this->primaryKey(),
            'name'=>$this->string(20)->notNull()->comment('姓名'),
            'age'=>$this->integer()->notNull()->comment('年龄'),
            'sex'=>$this->integer()->notNull()->comment('性别'),
            'head'=>$this->string(100)->notNull()->comment('头像'),
            'birthday'=>$this->integer()->notNull()->comment('生日'),
            'create_time'=>$this->integer()->notNull()->comment('创建时间')
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('author');
    }
}
