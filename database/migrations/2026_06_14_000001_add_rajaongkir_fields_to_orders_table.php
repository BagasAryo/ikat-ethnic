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
        Schema::table('orders', function (Blueprint $table) {
            // Kota tujuan dari RajaOngkir
            $table->unsignedInteger('shipping_city_id')->nullable()->after('shipping_address');
            $table->string('shipping_city_name')->nullable()->after('shipping_city_id');
            $table->string('shipping_province')->nullable()->after('shipping_city_name');
            // Kurir yang dipilih user
            $table->string('shipping_courier')->nullable()->after('shipping_province');
            // Layanan kurir (misal: REG, OKE, YES)
            $table->string('shipping_service')->nullable()->after('shipping_courier');
            // Estimasi tiba
            $table->string('shipping_etd')->nullable()->after('shipping_service');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'shipping_city_id',
                'shipping_city_name',
                'shipping_province',
                'shipping_courier',
                'shipping_service',
                'shipping_etd',
            ]);
        });
    }
};
