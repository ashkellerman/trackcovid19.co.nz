<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatisticsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statistics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id');	
            // Cases
            $table->string('cases_new')->nullable();
            $table->bigInteger('cases_active')->nullable();
            $table->bigInteger('cases_critical')->nullable();
            $table->bigInteger('cases_recovered')->nullable();
            $table->bigInteger('cases_total')->nullable();
            // Deaths
            $table->string('deaths_new')->nullable();
            $table->bigInteger('deaths_total')->nullable();
            
            // Tests
            $table->bigInteger('tests_total')->nullable();

            $table->dateTime('recorded_at')->nullable();
            

            $table->timestamps();

            $table->foreign('country_id')->references('id')->on('countries');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('statistics');
    }
}
