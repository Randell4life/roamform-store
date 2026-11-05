<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void { Schema::table('products', function(Blueprint $table) {
  $table->foreignId('category_id')->nullable()->after('id')->constrained()->nullOnDelete();
  $table->string('sku')->nullable()->unique(); $table->string('image_path')->nullable();
  $table->unsignedInteger('stock_quantity')->default(0); $table->unsignedInteger('low_stock_threshold')->default(5);
  $table->string('supplier_url',2048)->nullable();
 }); }
 public function down(): void { Schema::table('products', function(Blueprint $table) {
  $table->dropConstrainedForeignId('category_id'); $table->dropUnique(['sku']);
  $table->dropColumn(['sku','image_path','stock_quantity','low_stock_threshold','supplier_url']);
 }); }
};