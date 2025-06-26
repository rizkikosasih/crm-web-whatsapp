<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(): void
  {
    Schema::table('orders', function (Blueprint $table) {
      $table->text('link_pdf')->nullable()->after('status'); // sesuaikan posisi
    });
  }

  public function down(): void
  {
    Schema::table('orders', function (Blueprint $table) {
      $table->dropColumn('link_pdf');
    });
  }
};
