<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wedding_users', function (Blueprint $table) {
            $table->bigInteger('heading_title_id')->unsigned()->nullable();
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
        Schema::table('wedding_users', function (Blueprint $table) {
            $table->dropForeign('wedding_users_heading_title_id_foreign');
            $table->dropColumn('heading_title_id');
        });
    }
};
