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
        if (!Schema::hasTable('locations'))
        {
            Schema::create('locations', function (Blueprint $table) {
                $table->id();
                $table->text('name');
                $table->text('address');
                $table->text('slug')->nullable();
                $table->integer('created_by')->default(0);
                $table->integer('company_id')->default(0);
                $table->integer('workspace')->default(0);
                $table->integer('current_location')->default(0);
                $table->string('lang',5)->default('en');
                $table->integer('interval_time')->default(10);
                $table->string('currency')->default('$');
                $table->string('currency_code')->nullable();
                $table->string('company')->nullable();
                $table->string('city')->nullable();
                $table->string('state')->nullable();
                $table->string('zipcode')->nullable();
                $table->string('country')->nullable();
                $table->string('telephone')->nullable();
                $table->string('logo')->nullable();
                $table->integer('is_active')->default(1)->comment('1 => active || 0 => deactive');
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
        Schema::dropIfExists('locations');
    }
};
