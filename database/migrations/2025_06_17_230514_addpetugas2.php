<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
         Schema::table('pengajuan', function (Blueprint $table) {
            $table->enum('status_approval_2', ['pending', 'approved', 'rejected'])->default('pending')->after('status');
            $table->timestamp('approved_2_at')->nullable()->after('approved_at');
            $table->unsignedBigInteger('approved_2_by')->nullable()->after('approved_by');
            $table->timestamp('rejected_2_at')->nullable()->after('rejected_at');
            $table->unsignedBigInteger('rejected_2_by')->nullable()->after('rejected_by');

            $table->foreign('approved_2_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('rejected_2_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('pengajuan', function (Blueprint $table) {
            $table->dropForeign(['approved_2_by']);
            $table->dropForeign(['rejected_2_by']);
            $table->dropColumn([
                'status_approval_2',
                'approved_2_at',
                'approved_2_by',
                'rejected_2_at',
                'rejected_2_by',
            ]);
        });
    }
};
