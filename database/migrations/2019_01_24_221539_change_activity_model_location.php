<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ChangeActivityModelLocation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('journal_entries')
            ->where('journalable_type', 'App\Models\Contact\Activity')
            ->update(['journalable_type' => 'App\Models\Account\Activity']);
    }
}
