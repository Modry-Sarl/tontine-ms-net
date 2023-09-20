<?php

namespace App\Database\Migrations;

use BlitzPHP\Database\Migration\Migration;
use BlitzPHP\Database\Migration\Structure;

class CreateTableInscription extends Migration
{
    protected string $group = 'default';

    public function up()
    {
        $this->create('inscriptions', function(Structure $table) {
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('inscriptor');
            $table->integer('nbr');
            $table->ipAddress('ip');
            $table->string('ua');

            $table->timestamps();

            $table->foreign('user_id')->on('users')->references('id')->onDelete('CASCADE');
            $table->foreign('inscriptor')->on('users')->references('id')->onDelete('CASCADE');

            return $table;
        });
    }

    public function down()
    {
        $this->dropIfExists('inscriptions');
    }
}
