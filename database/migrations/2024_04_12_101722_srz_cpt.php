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
        Schema::create('srz_cpt', function (Blueprint $table) {
            // $table->charset('utf8mb4');
            // $table->collation('utf8mb4_unicode_ci');

            $table->id();
            $table->string('post_type');
            $table->string('post_title')->nullable()->default('');
            $table->longText('post_content');
            $table->string('post_status')->nullable()->default('publish');
            $table->string('post_slug')->nullable();     
            $table->string('post_parent')->nullable()->default(0); 
            $table->string('post_thumbnail')->nullable()->default(0);     
            $table->string('post_comment_status')->nullable()->default('open');

            $table->string('post_author')->foreignId('id')->constrained('users')->nullable();
    
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
        Schema::dropIfExists('srz_cpt');
    }
};
