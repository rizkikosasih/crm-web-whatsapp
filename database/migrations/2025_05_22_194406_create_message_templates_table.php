<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('message_templates', function (Blueprint $table) {
      $table->id();
      $table->string('title');
      $table->text('body');
      $table->enum('type', ['order', 'product', 'campaign'])->default('campaign');
      $table->boolean('is_active')->default(true);
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('message_templates');
  }
};
