<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void { Schema::create('orders', function (Blueprint $table) {
  $table->id(); $table->string('order_number',32)->unique();
  $table->string('customer_name'); $table->string('customer_email'); $table->string('customer_phone',40);
  $table->string('address_line_1'); $table->string('address_line_2')->nullable();
  $table->string('city'); $table->string('state_region')->nullable(); $table->string('postal_code',30);
  $table->string('country',100)->default('United States'); $table->text('customer_note')->nullable();
  $table->string('status',32)->default('pending_review'); $table->string('payment_status',32)->default('not_paid');
  $table->string('currency',3)->default('USD'); $table->decimal('subtotal',10,2);
  $table->decimal('shipping_cost',10,2)->default(0); $table->decimal('total',10,2);
  $table->timestamps(); $table->index(['status','created_at']);
 }); }
 public function down(): void { Schema::dropIfExists('orders'); }
};