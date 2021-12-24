<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchedulerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scheduler', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('database_source_id');
            $table->integer('storage_id');
            $table->json('database');
            $table->boolean('running');
            $table->string('remark')->nullable();
            $table->integer('user_created');
            $table->timestamps();

            // database_source_id foreign tbl:source 
            $table->foreign('database_source_id')
                  ->references('id')
                  ->on('database_sources');

            // storage_id foreign tbl:storage 
            $table->foreign('storage_id')
                  ->references('id')
                  ->on('storage');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scheduler');
    }
}
