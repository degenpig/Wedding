<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('owner_id')->unsigned()->nullable();
            $table->bigInteger('groom_id')->unsigned()->nullable();
            $table->bigInteger('bride_id')->unsigned()->nullable();
            $table->string('event_title')->nullable(); 
            $table->text('welcome_msg')->nullable();
            $table->date('event_date')->nullable();
            $table->time('event_time')->nullable();
            $table->text('location')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('google_maps_url')->nullable();
            $table->boolean('active')->default(1); 
            $table->boolean('deleted')->default(0); 
            $table->boolean('priority')->default(0);  
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('owner_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('groom_id')->references('id')->on('wedding_users')->onDelete('cascade');
            $table->foreign('bride_id')->references('id')->on('wedding_users')->onDelete('cascade');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
};
