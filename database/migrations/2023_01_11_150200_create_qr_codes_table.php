<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQrCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qr_codes', function (Blueprint $table) {
            $table->id();
            $table->string('qr_code')->nullable(); 
            $table->bigInteger('event_id')->unsigned()->nullable();
            $table->bigInteger('group_id')->unsigned()->nullable();
            $table->bigInteger('user_id')->unsigned()->nullable(); 
            $table->bigInteger('guest_id')->unsigned()->nullable();
            $table->bigInteger('companian_id')->unsigned()->nullable();
            $table->bigInteger('invitation_type_id')->unsigned()->nullable();
            $table->bigInteger('invitation_id')->unsigned()->nullable();
            $table->tinyInteger('scanned')->default(0)->index('scanned')->comment('0 - No, 1 - Yes'); 
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('guest_id')->references('id')->on('guests')->onDelete('cascade');
            $table->foreign('companian_id')->references('id')->on('companians')->onDelete('cascade');
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
        Schema::dropIfExists('qr_codes');
    }
};
