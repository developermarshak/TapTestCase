<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClicksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('click', function (Blueprint $table) {
            $table->string('id', 132);
            $table->string('ua', 1000);
            $table->string('ip');
            $table->string('referer', 2100);
            $table->string('param1', 1000);
            $table->string('param2', 1000)->default('');
            $table->integer('error')->default(0);
            $table->boolean('bad_domain')->default(false);

            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('click');
    }
}
