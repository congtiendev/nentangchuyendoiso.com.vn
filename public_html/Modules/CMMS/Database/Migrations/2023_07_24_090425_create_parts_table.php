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
        if (!Schema::hasTable('parts'))
        {
            Schema::create('parts', function (Blueprint $table) {
                $table->id();
                $table->text('name');
                $table->text('thumbnail')->nullable();
                $table->text('number')->nullable();
                $table->decimal('quantity')->default(0);
                $table->float('price')->default(0);
                $table->text('category')->nullable();
                $table->text('supplier_id')->nullable();
                $table->text('components_id')->nullable();
                $table->text('wo_id')->nullable();
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
        Schema::dropIfExists('parts');
    }
};
