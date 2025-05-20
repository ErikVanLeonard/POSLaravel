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
            // Add new fields
            $table->string('barcode')->unique()->nullable()->after('id'); // Made nullable
            $table->decimal('precio_menudeo', 8, 2)->default(0.00)->after('precio'); // Added default
            $table->decimal('precio_mayoreo', 8, 2)->default(0.00)->after('precio_menudeo'); // Added default
            $table->integer('stock_actual')->default(0)->after('cantidad'); // 'cantidad' is an existing column
            $table->unsignedBigInteger('category_id')->nullable()->after('stock_actual');
            $table->string('image_path')->nullable()->after('category_id');

            // Add foreign key for category_id
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['category_id']); // Ensure this matches Laravel's convention for dropping FKs
            $table->dropColumn(['barcode', 'precio_menudeo', 'precio_mayoreo', 'stock_actual', 'category_id', 'image_path']);
        });
    }
};
