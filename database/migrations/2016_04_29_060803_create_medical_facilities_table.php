<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateMedicalFacilitiesTable extends Migration
{
    private $tablename = "medical_facilities";
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tablename,function(Blueprint $table){
            $table->increments('id');
            $table->integer('village_id')->unsigned();
            $table->string('name');
            $table->string('address')->nullable();
            $table->string('type')->nullable();
            $table->foreign('village_id')->references('id')->on('villages')->onDelete('cascade')->onUpdate('cascade');
        });
        DB::statement("ALTER TABLE $this->tablename ADD COLUMN location geometry(Point)");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table($this->tablename,function(Blueprint $table){
            $table->dropForeign($this->tablename."_village_id_foreign");
        });
        Schema::drop($this->tablename);
    }
}
