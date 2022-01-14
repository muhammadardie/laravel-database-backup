<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldAutoPrunedayToSchedulerTable extends Migration
{

    public function up()
    {
        Schema::table('scheduler', function (Blueprint $table) {
            $table->integer('auto_prune_day')->nullable();
        });
    }

    public function down()
    {
        Schema::table('scheduler', function (Blueprint $table) {
            $table->dropColumn('auto_prune_day');
        });
    }
}
