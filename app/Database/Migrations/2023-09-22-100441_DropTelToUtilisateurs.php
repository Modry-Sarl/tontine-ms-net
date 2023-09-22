<?php

namespace App\Database\Migrations;

use BlitzPHP\Database\Migration\Migration;
use BlitzPHP\Database\Migration\Structure;

class DropTelToUtilisateurs extends Migration
{
    protected string $group = 'default';

    public function up()
    {
        $this->modify('utilisateurs', function(Structure $table) {
            $table->dropColumn('tel');
            $table->dropColumn('pays');

            return $table;
        });
    }

    public function down()
    {
        $this->modify('utilisateurs', function(Structure $table) {
            $table->string('tel', 15)->after('niveau');
            $table->string('pays', 4)->after('avatar')->default('cm');
        });
    }
}
