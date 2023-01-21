<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guests', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->bigInteger('event_id')->unsigned()->nullable(); 
            $table->bigInteger('group_id')->unsigned()->nullable();
            $table->bigInteger('heading_title_id')->unsigned()->nullable(); 
            $table->string('first_name')->nullable();
            $table->string('second_name')->nullable();
            $table->string('third_name')->nullable();
            $table->string('family_name')->nullable();
            $table->string('email_id')->nullable(); 
            $table->string('phone_code')->nullable(); 
            $table->string('phone_number')->nullable(); 
            $table->string('whatsapp_phone_code')->nullable(); 
            $table->string('whatsapp_phone_number')->nullable(); 
            $table->date('dob')->nullable();
            $table->string('age')->nullable(); 
            $table->tinyInteger('companian_available')->default(0)->index('companian_available')->comment('0 - Not Available, 1 - Available');  
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
            $table->foreign('heading_title_id')->references('id')->on('heading_titles')->onDelete('cascade');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('guests');
    }
};
