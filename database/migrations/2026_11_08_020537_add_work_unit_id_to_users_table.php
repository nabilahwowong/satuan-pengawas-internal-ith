<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('work_unit_id')
                  ->nullable()
                  ->after('email')
                  ->constrained('work_units')
                  ->nullOnDelete();
        });
    }

    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['work_unit_id']);
            $table->dropColumn('work_unit_id');
        });
    }
};
