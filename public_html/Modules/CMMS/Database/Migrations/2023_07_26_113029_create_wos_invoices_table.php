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
        if (!Schema::hasTable('wos_invoices'))
        {
            Schema::create('wos_invoices', function (Blueprint $table) {
                $table->id();
                $table->integer('wo_id')->nullable();
                $table->string('invoice_cost')->nullable();
                $table->text('description')->nullable();
                $table->string('invoice_file')->nullable();
                $table->integer('location_id')->default(0);
                $table->integer('created_by')->default(0);
                $table->integer('company_id')->default(0);
                $table->integer('Workspace')->default(0);
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
        Schema::dropIfExists('wos_invoices');
    }
};
