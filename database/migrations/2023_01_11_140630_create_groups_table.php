<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('groups', function (Blueprint $table) {
            $table->id(); 
            $table->bigInteger('event_id')->unsigned()->nullable();
            $table->bigInteger('user_id')->unsigned()->nullable(); 
            $table->string('title')->nullable();  
            $table->tinyInteger('active')->default(0)->index('active')->comment('0 - InActive, 1 - Active'); 
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    { 
        Schema::dropIfExists('groups');
    }
};
