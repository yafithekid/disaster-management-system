<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
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
        DB::statement("ALTER TABLE $this->tablename ADD COLUMN area geometry(Polygon)");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop($this->tablename);
    }
}
