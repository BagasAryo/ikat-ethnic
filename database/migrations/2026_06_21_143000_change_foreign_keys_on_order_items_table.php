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
        Schema::table('order_items', function (Blueprint $table) {
            // Drop existing foreign keys
            $table->dropForeign(['product_id']);
            $table->dropForeign(['product_size_id']);
        });

        Schema::table('order_items', function (Blueprint $table) {
            // Make columns nullable
            $table->foreignId('product_id')->nullable()->change();
            $table->foreignId('product_size_id')->nullable()->change();
        });

        Schema::table('order_items', function (Blueprint $table) {
            // Re-add foreign keys with onDelete('set null')
            $table->foreign('product_id')->references('id')->on('products')->onDelete('set null');
            $table->foreign('product_size_id')->references('id')->on('product_sizes')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Drop new foreign keys
            $table->dropForeign(['product_id']);
            $table->dropForeign(['product_size_id']);
        });

        Schema::table('order_items', function (Blueprint $table) {
            // Make columns non-nullable
            $table->foreignId('product_id')->nullable(false)->change();
            $table->foreignId('product_size_id')->nullable(false)->change();
        });

        Schema::table('order_items', function (Blueprint $table) {
            // Re-add original foreign keys with onDelete('cascade')
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('product_size_id')->references('id')->on('product_sizes')->onDelete('cascade');
        });
    }
};
