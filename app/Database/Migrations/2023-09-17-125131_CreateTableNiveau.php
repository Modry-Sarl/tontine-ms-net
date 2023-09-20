<?php

namespace App\Database\Migrations;

use BlitzPHP\Database\Migration\Migration;
use BlitzPHP\Database\Migration\Structure;

class CreateTableNiveau extends Migration
{
    protected string $group = 'default';

    public function up()
    {
        $this->create('niveaux', function(Structure $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedInteger('niveau');
            $table->timestamps();

            $table->foreign('user_id')->on('utilisateurs')->references('id')->onDelete('CASCADE');

            return $table;
        });
    }

    public function down()
    {
        $this->dropIfExists('niveaux');
    }
}
