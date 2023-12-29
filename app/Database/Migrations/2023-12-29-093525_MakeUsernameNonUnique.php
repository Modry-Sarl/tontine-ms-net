<?php

namespace App\Database\Migrations;

use BlitzPHP\Database\Migration\Migration;
use BlitzPHP\Database\Migration\Structure;

class MakeUsernameNonUnique extends Migration
{
    protected string $group = 'default';

    public function up()
    {
        $this->modify('users', function(Structure $table) {
            $table->dropUnique('username');

            return $table;
        });
    }

    public function down()
    {
        $this->modify('users', function(Structure $table) {
            $table->unique('username');

            return $table;
        });
    }
}
