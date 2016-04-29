<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDisasterAreasTable extends Migration
{
    private $tablename = "disaster_areas";
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tablename,function(Blueprint $table){
            $table->integer('disaster_id')->unsigned();
            $table->dateTime('start');
            $table->dateTime('end');
            $table->foreign('disaster_id')->references('id')->on('disasters')->onDelete('cascade')->onUpdate('cascade');
            $table->primary(['disaster_id','start','end']);
        });
        DB::statement("ALTER TABLE $this->tablename ADD COLUMN region geometry(Polygon)");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table($this->tablename,function(Blueprint $table){
            $table->dropForeign($this->tablename."_disaster_id_foreign");
        });
        Schema::drop($this->tablename);

    }
}
