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
        if (!Schema::hasTable('workorders'))
        {
            Schema::create('workorders', function (Blueprint $table) {
                $table->id();
                $table->integer('components_id')->nullable();
                $table->text('parts_id')->nullable();
                $table->integer('wo_id')->nullable();
                $table->text('wo_name')->nullable();
                $table->text('instructions')->nullable();
                $table->text('tags')->nullable();
                $table->string('priority')->nullable();
                $table->date('date')->nullable();
                $table->time('time')->nullable();
                $table->string('sand_to')->nullable();
                $table->integer('location_id')->default(0);
                $table->integer('created_by')->default(0);
                $table->integer('company_id')->default(0);
                $table->integer('workspace')->default(0);
                $table->string('hours')->nullable();
                $table->string('minute')->nullable();
                $table->integer('status')->default(1)->comment('1 => open, 2 => complete');
                $table->string('work_status')->nullable();
                $table->tinyInteger('is_active')->default(1);
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
        Schema::dropIfExists('workorders');
    }
};
