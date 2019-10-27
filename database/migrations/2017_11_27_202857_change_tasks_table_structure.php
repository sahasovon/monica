<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChangeTasksTableStructure extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->boolean('completed')->default(0)->after('description');
        });

        DB::table('tasks')
            ->where('status', 'completed')
            ->update(['completed' => 1]);

        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
