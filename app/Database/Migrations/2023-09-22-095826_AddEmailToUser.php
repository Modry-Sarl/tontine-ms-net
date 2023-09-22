<?php

namespace App\Database\Migrations;

use BlitzPHP\Database\Migration\Migration;
use BlitzPHP\Database\Migration\Structure;

class AddEmailToUser extends Migration
{
    protected string $group = 'default';

    public function up()
    {
        $this->modify('users', function(Structure $table) {
            $table->string('email', 128)->after('username')->unique();
            $table->string('pays', 4)->after('email')->default('cm');

            return $table;
        });
    }

    public function down()
    {
        $this->modify('users', function(Structure $table) {
            $table->dropColumn('email');
            $table->dropColumn('pays');

            return $table;
        });
    }
}
