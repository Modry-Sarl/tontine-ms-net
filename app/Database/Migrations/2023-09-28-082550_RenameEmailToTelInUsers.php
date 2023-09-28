<?php

namespace App\Database\Migrations;

use BlitzPHP\Database\Migration\Migration;
use BlitzPHP\Database\Migration\Structure;

class RenameEmailToTelInUsers extends Migration
{
    protected string $group = 'default';

    public function up()
    {
        $this->modify('users', function(Structure $table) {
            $table->renameColumn('email', 'tel');

            return $table;
        });
    }

    public function down()
    {
        $this->modify('users', function(Structure $table) {
            $table->renameColumn('tel', 'email');

            return $table;
        });
    }
}
