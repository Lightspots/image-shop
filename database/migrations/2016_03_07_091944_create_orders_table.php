<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('album_id')->unsigned();
            $table->foreign('album_id')->references('id')->on('albums');
            $table->string('firstname');
            $table->string('lastname');
            $table->string('street');
            $table->string('zip');
            $table->string('city');
            $table->string('email');
            $table->string('phone');
            $table->text('remark')->nullable();
            $table->decimal('price');
            $table->string('finish');
            $table->boolean('mailSend')->default(false);
            $table->boolean('deleted');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('orders');
    }
}
