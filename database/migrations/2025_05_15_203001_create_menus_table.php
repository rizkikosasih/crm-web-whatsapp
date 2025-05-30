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
    Schema::create('menus', function (Blueprint $table) {
      $table->id();
      $table->unsignedBigInteger('parent_id')->nullable();
      $table->string('name', 50);
      $table->string('icon', 50)->nullable();
      $table->tinyInteger('position');
      $table->string('route', 50)->default('#');
      $table->string('slug', 50)->nullable();
      $table->boolean('is_active')->default(true);
      $table->boolean('is_sidebar')->default(true);
      $table->timestamps();
      $table->softDeletes();

      //Relasi Table
      $table->foreign('parent_id')->references('id')->on('menus')->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('menus');
  }
};
