<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateVictimLocationsTable extends Migration
{
    private $tablename = "victim_locations";
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tablename,function(Blueprint $table){
            $table->dateTime('start');
            $table->dateTime('end');
            $table->integer('victim_id')->unsigned();
            $table->foreign('victim_id')->references('id')->on('victims')->onDelete('cascade')->onUpdate('cascade');
            $table->primary(['victim_id','start','end']);
        });
        DB::statement('ALTER TABLE victim_locations ADD COLUMN point geometry(Point)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table($this->tablename,function(Blueprint $table){
            $table->dropForeign('victim_locations_victim_id_foreign');
        });
        Schema::drop($this->tablename);
    }
}
