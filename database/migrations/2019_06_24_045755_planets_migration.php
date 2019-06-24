<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PlanetsMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('planetas', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nome');
            $table->string('clima');
            $table->string('terreno');
            $table->integer('cnt_aparicoes');
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('planetas', function (Blueprint $table) {
            $table->dropColumn(['id', 'nome', 'clima', 'terreno', 'cnt_aparicoes']);
        });
    }
}
