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
        Schema::create('events', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->integer('timeline_id');
            $table->string('title');
            $table->tinyInteger('date_type')->nullable();
            $table->smallInteger('date_none')->nullable();
            $table->smallInteger('date_year')->nullable();
            $table->tinyInteger('date_month')->nullable();
            $table->tinyInteger('date_day')->nullable();
            $table->time('date_time')->nullable();
            $table->bigInteger('date_unix')->nullable();
            $table->bigInteger('date_unix_gmt')->nullable();
            $table->string('period');
            $table->string('period_short');            
            $table->string('difference');
            $table->decimal('location_lat', 12,9)->nullable();
            $table->decimal('location_lng', 12,9)->nullable();
            $table->string('location')->nullable();
            $table->string('location_geo')->nullable();
            $table->tinyInteger('location_geo_street')->default(0);
            $table->smallInteger('location_show')->default(1); 
            $table->string('location_tz')->nullable();
            $table->tinyInteger('location_tz_error')->default(0);
            $table->smallInteger('order_ny')->default(0); 
            $table->smallInteger('order_ym')->default(0); 
            $table->smallInteger('order_md')->default(0); 
            $table->smallInteger('order_dt')->default(0); 
            $table->smallInteger('order_overall')->default(0); 
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
        Schema::dropIfExists('events');
    }
};
