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
    Schema::create('menu_roles', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('menu_id'); // Foreign key untuk menu
      $table->unsignedBigInteger('role_id'); // Foreign key untuk role

      $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');
      $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('menu_roles');
  }
};
