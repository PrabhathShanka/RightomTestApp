<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('loans', function (Blueprint $table) {
             $table->id();
            $table->string('customer_name');
            $table->decimal('principal', 15, 2);
            $table->integer('tenure');
            $table->decimal('interest_rate', 5, 2);
            $table->enum('loan_type', ['Flat', 'Reducing']);
            $table->decimal('emi', 15, 2);
            $table->decimal('remaining_principal', 15, 2);
            $table->enum('status', ['approved', 'rejected'])->default('approved');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
