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
        if (!Schema::hasTable('pms'))
        {
            Schema::create('pms', function (Blueprint $table) {
                $table->id();
                $table->text('name');
                $table->text('description')->nullable();
                $table->text('parts_id')->nullable();
                $table->string('tags')->nullable();
                $table->integer('location_id');
                $table->integer('created_by')->nullable();
                $table->integer('company_id')->nullable();
                $table->integer('workspace')->nullable();
                $table->integer('is_active')->nullable();
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
        Schema::dropIfExists('pms');
    }
};
