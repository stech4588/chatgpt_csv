<?php

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
        Schema::create('csv_data1', function (Blueprint $table) {
            $table->id();
            $table->string('col'); // Replace with your column names
            $table->string('row');
            $table->text('prompt')->nullable(); // Replace with your column names
            $table->text('data')->nullable(); // Replace with your column names
            $table->string('file_path')->nullable();
            $table->integer('created_by')->unsigned()->nullable();
            $table->foreign('created_by')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('csv_data1');
    }
};
