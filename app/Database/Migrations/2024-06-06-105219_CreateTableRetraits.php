<?php

namespace App\Database\Migrations;

use BlitzPHP\Database\Migration\Migration;
use BlitzPHP\Database\Migration\Structure;

class CreateTableRetraits extends Migration
{
    protected string $group = 'default';

    public function up()
    {
        $this->create('retraits', function(Structure $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('ref', 15)->unique();
            $table->string('tel', 15);
            $table->enum('compte', ['principal', 'bonus', 'recharge']);
            $table->float('montant');
            $table->string('service', 20)->default('monetbil');
            $table->enum('statut', ['pending', 'validated', 'rejected'])->default('pending');
            $table->timestamp('process_at')->nullable();
            $table->unsignedBigInteger('process_by')->nullable();
            $table->string('rejected_reason')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->on('utilisateurs')->references('id')->onDelete('CASCADE');
            $table->foreign('process_by')->on('utilisateurs')->references('id')->nullOnDelete();

            return $table;
        });
    }

    public function down()
    {
        $this->dropIfExists('retraits');
    }
}
