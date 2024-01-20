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
        if (!Schema::hasTable('cmms_pos'))
        {
            Schema::create('cmms_pos', function (Blueprint $table) {
                $table->id();
                $table->integer('parts_id')->nullable();
                $table->integer('wo_id')->nullable();
                $table->integer('supplier_id')->nullable();
                $table->integer('user_id')->nullable();
                $table->integer('budgets_id')->nullable();
                $table->date('pos_date')->nullable();
                $table->date('delivery_date')->nullable();
                $table->integer('location_id')->default(0);
                $table->integer('created_by')->default(0);
                $table->integer('company_id')->default(0);
                $table->integer('is_active')->default(1);
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
        Schema::dropIfExists('cmms_pos');
    }
};
