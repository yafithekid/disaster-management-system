<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateRefugeCampsTable extends Migration
{
    private $tablename = "refuge_camps";
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tablename,function(Blueprint $table){
            $table->integer('village_id');
            $table->string('name');
            $table->string('address')->nullable();
            $table->integer('capacity');
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
            $table->dropForeign("$this->tablename"."_village_id_foreign");
        });
        Schema::drop($this->tablename);

    }
}
