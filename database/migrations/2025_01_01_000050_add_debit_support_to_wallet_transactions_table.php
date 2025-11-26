<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('wallet_transactions', function (Blueprint $table): void {
            // Add transaction type: 'credit' (top-up) or 'debit' (spending)
            $table->string('type')->default('credit')->after('status');
            
            // Add transaction_id for idempotency (partner-provided unique ID)
            $table->string('transaction_id')->nullable()->after('reference');
            
            // Add reason field for debit transactions
            $table->text('reason')->nullable()->after('amount');
            
            // Make callback_url, success_url, cancel_url nullable (not needed for debits)
            $table->string('callback_url')->nullable()->change();
            $table->string('success_url')->nullable()->change();
            $table->string('cancel_url')->nullable()->change();
            
            // Add unique index on transaction_id for idempotency
            $table->unique('transaction_id');
        });
    }

    public function down(): void
    {
        Schema::table('wallet_transactions', function (Blueprint $table): void {
            $table->dropUnique(['transaction_id']);
            $table->dropColumn(['type', 'transaction_id', 'reason']);
            
            // Revert nullable changes (though we can't easily restore NOT NULL)
            $table->string('callback_url')->nullable(false)->change();
            $table->string('success_url')->nullable(false)->change();
            $table->string('cancel_url')->nullable(false)->change();
        });
    }
};

