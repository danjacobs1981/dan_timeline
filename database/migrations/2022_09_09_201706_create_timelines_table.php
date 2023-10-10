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
        Schema::create('timelines', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->foreignId('user_id')->constrained();
            $table->tinyInteger('privacy')->default(0);
            $table->string('title');
            $table->string('slug');
            $table->boolean('map')->default(1);
            $table->boolean('comments')->default(1);
            $table->boolean('comments_event')->default(1);
            $table->boolean('filter')->default(1);
            $table->boolean('social')->default(1);
            $table->boolean('collab')->default(1);
            $table->boolean('profile')->default(1);
            $table->boolean('adverts')->default(1);
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
        Schema::dropIfExists('timelines');
    }
};
