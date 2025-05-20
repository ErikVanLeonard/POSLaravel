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
        Schema::table('products', function (Blueprint $table) {
            // Hacer que la columna 'precio' sea anulable con un valor predeterminado de 0
            $table->decimal('precio', 10, 2)->default(0)->nullable()->change();
            
            // Hacer que la columna 'cantidad' sea anulable con un valor predeterminado de 0
            $table->integer('cantidad')->default(0)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Revertir los cambios si es necesario
            $table->decimal('precio', 10, 2)->default(0)->change();
            $table->integer('cantidad')->change();
        });
    }
};
