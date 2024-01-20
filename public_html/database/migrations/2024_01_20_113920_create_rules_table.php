<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRulesTable extends Migration
{
    public function up()
    {
        Schema::create('rules', function (Blueprint $table) {
            $table->id();
            $table->time('checkin_time');
            $table->time('checkout_time');
            $table->text('violation_handling')->nullable();
            $table->text('attendance_calculation')->nullable();
            $table->text('shift_registration')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rules');
    }
}
