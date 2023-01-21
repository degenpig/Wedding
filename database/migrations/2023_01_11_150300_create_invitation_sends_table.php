<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvitationSendsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invitation_sends', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('type')->default('0')->index('type')->comment('0 - Email, 1 - SMS, 2 - WhatsApp');
            $table->string('title')->nullable();
            $table->string('welcome_message')->nullable();
            $table->bigInteger('event_id')->unsigned()->nullable();
            $table->bigInteger('sender_id')->unsigned()->nullable();
            $table->bigInteger('receiver_id')->unsigned()->nullable();
            $table->bigInteger('companian_id')->unsigned()->nullable();
            $table->bigInteger('invitation_type_id')->unsigned()->nullable(); 
            $table->bigInteger('invitation_id')->unsigned()->nullable(); 
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('receiver_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('companian_id')->references('id')->on('companians')->onDelete('cascade');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->foreign('invitation_type_id')->references('id')->on('invitation_types')->onDelete('cascade');
            $table->foreign('invitation_id')->references('id')->on('invitations')->onDelete('cascade');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invitation_sends');
    }
};
