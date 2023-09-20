<?php

namespace App\Database\Migrations;

use BlitzPHP\Database\Migration\Migration;
use BlitzPHP\Database\Migration\Structure;

class CreateTableUsers extends Migration
{
    protected string $group = 'default';

    public function up()
    {
        $this->create('utilisateurs', function(Structure $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('ref', 15)->unique();
            $table->string('parrain', 15)->nullable();
            $table->boolean('main')->default(false);
            $table->enum('pack', ['argent', 'or', 'diament'])->default('argent');
            $table->integer('niveau')->unsigned()->default(0);
            $table->string('tel', 15);
            $table->float('solde_principal')->unsigned()->default(0);
            $table->float('solde_recharge')->unsigned()->default(0);
            $table->enum('sexe', ['f', 'm'])->nullable();
            $table->string('avatar')->nullable();
            $table->string('pays', 4)->default('cm');

            $table->timestamps();
            
            $table->foreign('user_id')->on('users')->references('id')->onDelete('CASCADE');
            $table->foreign('parrain')->on('utilisateurs')->references('ref')->onDelete('SET NULL');

            return $table;
        });
    }

    public function down()
    {
        $this->dropIfExists('utilisateurs');
    }
}
