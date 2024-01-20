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
        if (!Schema::hasTable('forms'))
        {
            Schema::create('forms', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('pms_id')->nullable();
                $table->integer('is_active')->default(1);
                $table->text('json');
                $table->integer('created_by')->nullable();
                $table->integer('company_id')->nullable();
                $table->integer('workspace')->nullable();
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
        Schema::dropIfExists('forms');
    }
};
