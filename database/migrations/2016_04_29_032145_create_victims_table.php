<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateVictimsTable extends Migration
{
    private $tablename = "victims";
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
            $table->string('phone_no')->nullable();
            $table->string('address')->nullable();
            $table->enum('gender',['m','f']);
            $table->date('date_of_birth');
            $table->string('heir')->nullable();
        });
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
