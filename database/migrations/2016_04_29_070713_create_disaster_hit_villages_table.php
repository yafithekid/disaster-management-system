<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDisasterHitVillagesTable extends Migration
{
    private $tablename = "disaster_hit_villages";
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tablename,function(Blueprint $table){
            $table->integer('disaster_event_id')->unsigned();
            $table->integer('village_id')->unsigned();
            $table->dateTime('start');
            $table->dateTime('end');
            $table->string('weather_condition');
            $table->foreign('disaster_event_id')->references('id')->on('disaster_events')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('village_id')->references('id')->on('villages')->onDelete('cascade')->onUpdate('cascade');
            $table->primary(['disaster_event_id','village_id']);
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
            $table->dropForeign($this->tablename."_disaster_event_id_foreign");
            $table->dropForeign($this->tablename."_village_id_foreign");
        });
        Schema::drop($this->tablename);
    }
}
