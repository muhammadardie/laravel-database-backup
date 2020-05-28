<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBackupHistoriesTable extends Migration
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
            $table->integer('source_id');
            $table->integer('disk_id');
            $table->string('path')->nullable();
            $table->string('name');
            $table->string('database');
            $table->integer('user_created');
            $table->timestamps();

            // source_id foreign tbl:source 
            $table->foreign('source_id')
                  ->references('id')
                  ->on('sources');

            // disk_id foreign tbl:disk 
            $table->foreign('disk_id')
                  ->references('id')
                  ->on('disks');
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
}
