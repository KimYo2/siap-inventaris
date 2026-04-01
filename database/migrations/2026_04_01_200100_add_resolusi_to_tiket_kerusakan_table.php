<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tiket_kerusakan', function (Blueprint $table) {
            $table->enum('resolusi', ['diperbaiki', 'dihapuskan', 'hilang', 'diabaikan'])
                  ->nullable()
                  ->after('status');
            $table->text('catatan_resolusi')->nullable()->after('resolusi');
            $table->timestamp('diselesaikan_at')->nullable()->after('catatan_resolusi');
            $table->foreignId('diselesaikan_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete()
                  ->after('diselesaikan_at');
        });
    }

    public function down(): void
    {
        Schema::table('tiket_kerusakan', function (Blueprint $table) {
            $table->dropForeign(['diselesaikan_by']);
            $table->dropColumn(['resolusi', 'catatan_resolusi', 'diselesaikan_at', 'diselesaikan_by']);
        });
    }
};
