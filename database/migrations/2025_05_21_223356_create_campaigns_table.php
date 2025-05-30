<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::create('campaigns', function (Blueprint $table) {
      $table->id();
      $table->string('title');
      $table->text('message');
      $table->string('image', 255)->nullable();
      $table->string('image_url', 255)->nullable();
      $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
      $table->timestamps();
      $table->softDeletes();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('campaigns');
  }
};
