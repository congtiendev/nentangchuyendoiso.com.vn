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
        if (!Schema::hasTable('components_fields'))
        {
            Schema::create('components_fields', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('type');
                $table->string('module');
                $table->integer('created_by')->default(0);
                $table->integer('company_id')->default(0);
                $table->tinyinteger('is_active')->default(1);
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
        Schema::dropIfExists('components_fields');
    }
};
