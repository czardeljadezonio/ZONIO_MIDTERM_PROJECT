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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->date('start_date');
            $table->unsignedBigInteger('plan_id')->nullable(); // Foreign key, nullable
            $table->timestamps();

            // Foreign Key Constraint with onDelete('set null')
            $table->foreign('plan_id')
                  ->references('id')
                  ->on('plans')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
