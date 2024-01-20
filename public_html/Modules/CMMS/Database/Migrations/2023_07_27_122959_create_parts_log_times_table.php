<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('parts_log_times'))
        {
            Schema::create('parts_log_times', function (Blueprint $table) {
                $table->id();
                $table->integer('parts_id')->nullable();
                $table->integer('user_id')->nullable();
                $table->string('hours')->nullable();
                $table->string('minute')->nullable();
                $table->date('date')->nullable();
                $table->text('description')->nullable();
                $table->integer('location_id')->default(0);
                $table->integer('created_by')->default(0);
                $table->integer('company_id')->default(0);
                $table->integer('workspace')->default(0);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parts_log_times');
    }
};
