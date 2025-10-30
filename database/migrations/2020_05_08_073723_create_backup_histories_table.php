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
        Schema::create('backup_histories', function (Blueprint $table) {
            $table->id();
            $table->integer('database_source_id');
            $table->integer('storage_id');
            $table->integer('scheduler_id')->nullable();
            $table->integer('user_created');
            $table->string('filename');
            $table->string('database');
            $table->enum('backup_type', ['manual', 'automatic']);
            $table->timestamps();

            // database_source_id foreign tbl:source 
            $table->foreign('database_source_id')
                  ->references('id')
                  ->on('database_sources');

            // storage_id foreign tbl:storage 
            $table->foreign('storage_id')
                  ->references('id')
                  ->on('storage');

            // scheduler_id foreign tbl:scheduler 
            $table->foreign('scheduler_id')
                  ->references('id')
                  ->on('scheduler');

            // user_created foreign tbl:users 
            $table->foreign('user_created')
                  ->references('id')
                  ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('backup_histories');
    }
};