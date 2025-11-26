<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('wallet_transactions', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('wallet_account_id')->constrained('wallet_accounts')->cascadeOnDelete();
            $table->string('reference')->index();
            $table->unsignedBigInteger('partner_purchase_id')->nullable();
            $table->unsignedBigInteger('credits');
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('CHF');
            $table->string('status')->default('pending');
            $table->string('stripe_payment_intent')->nullable();
            $table->string('stripe_session_id')->nullable();
            $table->json('payload')->nullable();
            $table->string('callback_url');
            $table->string('success_url');
            $table->string('cancel_url');
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->unique(['reference', 'wallet_account_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};


