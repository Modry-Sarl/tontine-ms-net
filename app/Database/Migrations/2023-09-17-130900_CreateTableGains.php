<?php

namespace App\Database\Migrations;

use BlitzPHP\Database\Migration\Migration;
use BlitzPHP\Database\Migration\Structure;

class CreateTableGains extends Migration
{
    protected string $group = 'default';

    public function up()
    {
        $this->create('gains', function(Structure $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedInteger('niveau');
            $table->float('montant_reel');
            $table->float('montant_recu');
            $table->timestamps();

            $table->foreign('user_id')->on('utilisateurs')->references('id')->onDelete('CASCADE');

            return $table;
        });
    }

    public function down()
    {
        $this->dropIfExists('gains');
    }
}
