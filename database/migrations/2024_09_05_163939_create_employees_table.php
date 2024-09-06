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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Nome do Funcionário');
            $table->unsignedTinyInteger('age')->comment('Idade do Funcionário');
            $table->decimal('salary', 15, 2)->comment('Salário do Funcionário');
            $table->date('date_admission')->comment('Data de Admissão');
            $table->date('date_dismissal')->nullable()->comment('Data de Demissão');
            $table->unsignedInteger('vacation')->default(0)->comment('Quantidade de Férias Tiradas');
            $table->foreignId('position_id')->constrained('positions')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
