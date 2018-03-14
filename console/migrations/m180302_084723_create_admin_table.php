<?php

use yii\db\Migration;

/**
 * Handles the creation of table `admin`.
 */
class m180302_084723_create_admin_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('admin', [
            'id' => $this->primaryKey(),
//username  varchar(255) NOT NULL
            'username'=>$this->string(255)->comment('用户名')->notNull(),
//auth_key  varchar(32) NOT NULL
            'auth_key'=>$this->string(32)->notNull(),
//password_hash varchar(255) NOT NULL
            'password_hash'=>$this->string(255)->notNull(),
//password_reset_token varchar(255) NULL
            'password_reset_token'=>$this->string(255),
//email varchar(255) NOT NULL
            'email'=>$this->string(255)->notNull(),
//status smallint(6) NOT NULL
            'status'=>$this->smallInteger(6)->notNull(),
//created_at int(11) NOT NULL
            'create_at'=>$this->integer(11)->notNull(),
//updated_at int(11) NOT NULL
            'updated_at'=>$this->integer(11)->notNull(),
            'last_login_time' => $this->integer()->notNull(),
            'last_login_ip' => $this->char(15)->notNull(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('admin');
    }
}
