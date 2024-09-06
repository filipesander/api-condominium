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
        Schema::create('owners', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Nome Morador');
            $table->string('cpf', 11)->unique()->comment('CPF');
            $table->string('number')->comment('Número de Telefone');
            $table->date('birth_date')->comment('Data de Nascimento');
            $table->string('email')->unique()->comment('Email');
            $table->unsignedInteger('tower')->comment('Torre do Morador');
            $table->string('apartment_number')->comment('Número do Apartamento');
            $table->string('garage')->comment('Garagem do Morador');
            $table->boolean('rented')->default(false)->comment('Apartamento está alugado (TRUE|FALSE)');
            $table->boolean('paid')->default(false)->comment('Apartamento Quitado (TRUE|FALSE)');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('owners');
    }
};
