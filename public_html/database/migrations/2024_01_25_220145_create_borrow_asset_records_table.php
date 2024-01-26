<?php

use App\Models\BorrowAssetRecord;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('borrow_asset_records', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('asset_id')->nullable();
            $table->integer('employee_id')->nullable();
            $table->string('status')->default(BorrowAssetRecord::$statues[0]);
            $table->date('borrowed_date')->nullable();
            $table->integer('borrowed_day')->nullable();
            $table->date('give_back_day')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrow_asset_records');
    }
};
