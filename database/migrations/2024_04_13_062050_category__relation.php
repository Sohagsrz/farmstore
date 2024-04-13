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
        Schema::create('category_relation', function (Blueprint $table) {
            $table->id(); 
            $table->integer('category_id')->nullable()->foriegn('category_id')->references('id')->on('category')->onDelete('cascade');
            $table->integer('post_id')->nullable()->foriegn('post_id')->references('id')->on('srz_cpt')->onDelete('cascade'); 
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
        //
    }
};
