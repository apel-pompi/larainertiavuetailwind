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
        Schema::create('branches', function (Blueprint $table) {
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';
            $table->id();
            $table->string('branchname')->unique();
            $table->text('description')->nullable();
            $table->string('contacperson',50)->nullable();
            $table->string('designation',50)->nullable();
            $table->string('mailingaddress')->nullable();
            $table->string('emailaddress')->unique();
            $table->string('phone',20)->nullable();
            $table->integer('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
