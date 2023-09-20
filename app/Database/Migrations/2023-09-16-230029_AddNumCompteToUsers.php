<?php

namespace App\Database\Migrations;

use BlitzPHP\Database\Migration\Migration;
use BlitzPHP\Database\Migration\Structure;

class AddNumCompteToUsers extends Migration
{
    protected string $group = 'default';

    public function up()
    {
        $this->modify('users', function(Structure $table) {
            $table->string('num_compte', 20)->after('username')->unique();
            
            return $table;
        });
    }
    
    public function down()
    {
        $this->modify('users', function(Structure $table) {
            $table->dropColumn('num_compte');
            
            return $table;
        });
    }
}
