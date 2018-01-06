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
            $table->uuid('id');
            $table->string('ua', 1000);
            $table->string('ip');
            $table->string('referrer', 1000);
            $table->string('param1', 1000);
            $table->string('param2', 1000)->default('');
            $table->integer('error')->default(0);
            $table->boolean('bad_domain')->default(false);

            $table->primary('id');

            $table->unique([
                'ua',
                'ip',
                'referrer',
                'param1'
            ]);
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
