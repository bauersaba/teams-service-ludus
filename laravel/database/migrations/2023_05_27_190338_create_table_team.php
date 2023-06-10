<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('team', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('coach_id');
            $table->foreign('coach_id')->references('id')->on('coach');
            $table->string('popular_name')->nullable();
            $table->string('nickname_club')->nullable();
            $table->string('name_club');
            $table->string('acronym_club')->nullable();
            $table->string('shield_club')->nullable();
            $table->timestamps();
            $table->softDeletes(); 
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('team');
    }
};
