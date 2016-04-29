<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateVictimStatusesTable extends Migration
{
    private $tablename = "victim_statuses";
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tablename,function(Blueprint $table){
            $table->integer("victim_id")->unsigned();
            $table->dateTime('start');
            $table->dateTime('end');
            $table->enum('status',['affected','minor_injury','major_injury','missing','deceased']);
            $table->foreign("victim_id")->references('id')->on('victims')->onDelete('cascade')->onUpdate('cascade');
            $table->primary(['victim_id','start','end']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table($this->tablename,function(Blueprint $table){
            $table->dropForeign($this->tablename."_victim_id_foreign");
        });
        Schema::drop($this->tablename);
    }
}
