<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            $table->string('shipping_carrier', 100)->nullable()->after('shipping_cost');
            $table->string('tracking_number', 150)->nullable()->after('shipping_carrier');
            $table->string('tracking_url', 2048)->nullable()->after('tracking_number');
            $table->timestamp('shipped_at')->nullable()->after('tracking_url');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table): void {
            $table->dropColumn(['shipping_carrier', 'tracking_number', 'tracking_url', 'shipped_at']);
        });
    }
};
