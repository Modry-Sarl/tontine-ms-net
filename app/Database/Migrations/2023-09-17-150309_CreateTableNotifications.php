<?php

namespace App\Database\Migrations;

use BlitzPHP\Database\Migration\Migration;
use BlitzPHP\Database\Migration\Structure;

class CreateTableNotifications extends Migration
{
    protected string $group = 'default';

    public function up()
    {
        $this->create('notifications', function(Structure $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('libelle', 128);
            $table->text('description');
            $table->boolean('lu')->default(false);
            $table->string('type', 20);
            $table->timestamps();

            $table->foreign('user_id')->on('utilisateurs')->references('id')->onDelete('CASCADE');

            return $table;
        });
    }

    public function down()
    {
        $this->dropIfExists('notifications');
    }
}
