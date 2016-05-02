<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateVillagesTable extends Migration
{
    private $tablename = 'villages';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->tablename,function(Blueprint $table){
            $table->increments('id');
            $table->string('name');
            $table->string('subdistrict');
            $table->string('district');
            $table->string('province');
        });
        DB::statement("ALTER TABLE $this->tablename ADD COLUMN geom geometry(Point)");
        Schema::table("victims",function(Blueprint $table){
            $table->integer("village_id")->unsigned();
            $table->foreign("village_id")->references("id")->on("villages")->onDelete("cascade")->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("victims",function(Blueprint $table){
            $table->dropForeign("victims_village_id_foreign");
        });
        Schema::drop($this->tablename);
    }
}
