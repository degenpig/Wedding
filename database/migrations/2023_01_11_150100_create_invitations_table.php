<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invitations', function (Blueprint $table) {
            $table->id();
            $table->string('invitation_code')->nullable();
            $table->bigInteger('sender_id')->unsigned()->nullable();
            $table->bigInteger('receiver_id')->unsigned()->nullable();
            $table->bigInteger('event_id')->unsigned()->nullable();
            $table->bigInteger('invitation_type_id')->unsigned()->nullable(); 
            $table->bigInteger('invitation_status_id')->unsigned()->nullable();
            $table->tinyInteger('receiver_companian')->default(0)->index('receiver_companian')->comment('0 - Not Available, 1 - Available');
            $table->tinyInteger('active')->default(0)->index('active')->comment('0 - InActive, 1 - Active'); 
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('receiver_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->foreign('invitation_type_id')->references('id')->on('invitation_types')->onDelete('cascade');
            $table->foreign('invitation_status_id')->references('id')->on('statuses')->onDelete('cascade');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invitations');
    }
};
