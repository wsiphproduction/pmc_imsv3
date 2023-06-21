<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInsertTriggerForPOTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared("CREATE TRIGGER `tr_insert_po` AFTER INSERT ON `po` FOR EACH ROW 
                        BEGIN
                            INSERT INTO po_activity_logs (ref_id, poId, action, log_date, users, log_desc)
                            Values (new.id,new.poId,'insert',NOW(),new.addedBy,'created the ');    
                        END");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER `tr_insert_po`');
    }
}
