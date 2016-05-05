<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateDisastersTable extends Migration
{
    private $tablename = "disasters";
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tablename,function(Blueprint $table){
            $table->increments('id');
            $table->integer('disaster_event_id')->unsigned();
            $table->string('type');

            $table->foreign('disaster_event_id')->references('id')->on('disaster_events')
            ->onDelete('cascade')->onUpdate('cascade');
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
        });
        Schema::drop($this->tablename);
    }
}
