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
            // Eliminar las columnas que vamos a modificar
            $table->dropColumn(['precio', 'cantidad']);
        });

        Schema::table('products', function (Blueprint $table) {
            // Volver a crear las columnas con las configuraciones correctas
            $table->decimal('precio', 10, 2)->default(0)->nullable()->after('nombre');
            $table->integer('cantidad')->default(0)->after('precio');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Esta es una operación destructiva, así que ten cuidado al revertir
        // Si necesitas restaurar el estado anterior, asegúrate de tener una copia de seguridad
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['precio', 'cantidad']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->decimal('precio', 10, 2);
            $table->integer('cantidad');
        });
    }
};
