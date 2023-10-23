<?php

namespace App\Database\Migrations;

use BlitzPHP\Database\Migration\Migration;
use BlitzPHP\Database\Migration\Structure;

class ChangeTypeOfPackInUtilisateurs extends Migration
{
    protected string $group = 'default';

    public function up()
    {
        $this->modify('utilisateurs', function(Structure $table) {
            $table->enum('pack', ['argent', 'or', 'diamant'])->default('argent')->change();

            return $table;
        });
    }

    public function down()
    {
        $this->modify('utilisateurs', function(Structure $table) {
            $table->enum('pack', ['argent', 'or', 'diament'])->default('argent')->change();

            return $table;
        });
    }
}
