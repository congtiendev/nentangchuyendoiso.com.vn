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
        if (!Schema::hasTable('suppliers'))
        {
            Schema::create('suppliers', function (Blueprint $table) {
                $table->id();
                $table->string('name')->nullable();
                $table->string('contact')->nullable();
                $table->string('email')->nullable();
                $table->string('phone')->nullable();
                $table->text('address')->nullable();
                $table->text('components_id')->nullable();
                $table->text('parts_id')->nullable();
                $table->string('image')->nullable();
                $table->integer('location_id')->default(0);
                $table->integer('created_by')->default(0);
                $table->integer('company_id')->default(0);
                $table->integer('workspace')->default(0);
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
        Schema::dropIfExists('suppliers');
    }
};
