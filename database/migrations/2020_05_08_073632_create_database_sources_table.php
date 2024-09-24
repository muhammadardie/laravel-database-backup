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
        Schema::create('database_sources', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['postgresql'])->default('postgresql');
            $table->string('host');
            $table->string('username');
            $table->string('password');
            $table->integer('port')->default(5432);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('database_sources');
    }
};