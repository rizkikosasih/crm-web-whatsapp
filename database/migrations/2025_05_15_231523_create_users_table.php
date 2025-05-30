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
    Schema::create('users', function (Blueprint $table) {
      $table->id();
      $table->string('name', 128);
      $table->string('username')->unique();
      $table->string('password', 256);
      $table->string('email')->unique();
      $table->string('phone', 16)->nullable();
      $table->timestamp('email_verified_at')->nullable();
      $table->unsignedBigInteger('role_id');
      $table->text('address')->nullable();
      $table->string('avatar', 255)->nullable();
      $table->timestamp('last_login_at')->nullable();
      $table->string('last_login_ip', 45)->nullable();
      $table->boolean('is_active')->default(true);
      $table->boolean('is_delete')->default(false);
      $table->timestamps();
      $table->softDeletes();
      $table->index('username', 'idx_username', 'hash');
      $table->index('email', 'idx_email', 'hash');
      $table->index('id', 'idx_id', 'hash');

      //Relasi Table
      $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('users');
  }
};
