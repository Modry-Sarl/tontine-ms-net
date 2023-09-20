<?php

namespace App\Database\Migrations;

use BlitzPHP\Database\Migration\Migration;
use BlitzPHP\Database\Migration\Structure;

class CreateTableTransactions extends Migration
{
    protected string $group = 'default';

    public function up()
    {
        $this->create('transactions', function(Structure $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('numero', 20);
            $table->string('ref', 35)->nullable();
            $table->float('montant')->unsigned();
            $table->integer('frais')->unsigned();
            $table->enum('type', ['entree', 'sortie', 'transfert']);
            $table->tinyInteger('statut')->unsigned();
            $table->string('message', 25);
            $table->string('operateur', 25)->nullable();
            $table->string('reference', 25)->nullable()->comment('reference provenant de l\'operateur. a ne pas confondre avec les references MS');
            $table->string('operator_transaction_id', 35);
            $table->ipAddress('ip');
            $table->dateTime('dt');
            $table->string('ua');

            $table->timestamps();

            $table->foreign('user_id')->on('utilisateurs')->references('id')->onDelete('SET NULL');

            return $table;
        });
    }

    public function down()
    {
        $this->dropIfExists('transactions');
    }
}
