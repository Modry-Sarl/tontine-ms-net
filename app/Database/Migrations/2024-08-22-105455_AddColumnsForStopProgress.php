<?php

namespace App\Database\Migrations;

use BlitzPHP\Database\Migration\Migration;
use BlitzPHP\Database\Migration\Structure;

class AddColumnsForStopProgress extends Migration
{
    protected string $group = 'default';

    public function up()
    {
        $this->modify('utilisateurs', function(Structure $table) {
            $table->boolean('lock')->default(false)->after('pack');

            return $table;
        });

        $this->modify('gains', function(Structure $table) {
            $table->boolean('direct')->default(true)->after('montant_recu');

            return $table;
        });
    }

    public function down()
    {
        $this->modify('utilisateurs', function(Structure $table) {
            $table->dropColumn('lock');

            return $table;
        });

        $this->modify('gains', function(Structure $table) {
            $table->dropColumn('direct');

            return $table;
        });
    }
}
