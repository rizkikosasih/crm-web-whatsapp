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
      $table->enum('status', ['draft', 'scheduled', 'sent'])->default('draft');
      $table->timestamp('schedule_at')->nullable();
      $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
      $table->timestamps();
    });
  }

  public function down(): void
  {
    Schema::dropIfExists('campaigns');
  }
};

