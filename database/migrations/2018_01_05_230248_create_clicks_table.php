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
        Schema::create('clicks', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('ua', 1000);
            $table->string('ip');
            $table->string('referrer', 1000);
            $table->string('param1', 1000);
            $table->string('param2', 1000);
            $table->integer('error');
            $table->boolean('bad_domain');

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
        Schema::dropIfExists('clicks');
    }
}
