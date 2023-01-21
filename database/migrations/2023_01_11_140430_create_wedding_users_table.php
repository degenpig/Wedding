<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWeddingUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wedding_users', function (Blueprint $table) {
            $table->id();
             $table->bigInteger('owner_id')->unsigned()->nullable();
            $table->tinyInteger('type')->default(0)->index('type')->comment('0 - Groom, 1 - Bride');
            $table->string('first_name')->nullable();
            $table->string('second_name')->nullable();
            $table->string('third_name')->nullable();
            $table->string('family_name')->nullable();
            $table->string('father_name')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('email_id')->nullable(); 
            $table->string('phone_code')->nullable(); 
            $table->string('phone_number')->nullable(); 
            $table->date('dob')->nullable();
            $table->string('age')->nullable();
            $table->string('civil_id_number')->nullable();
            $table->string('civil_id_image')->nullable();
            $table->string('nationality')->nullable();  
            $table->tinyInteger('active')->default(0)->index('active')->comment('0 - InActive, 1 - Active');
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('owner_id')->references('id')->on('users')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wedding_users');
    }
};
