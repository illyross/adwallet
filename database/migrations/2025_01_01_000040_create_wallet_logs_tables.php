<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('wallet_sso_events', function (Blueprint $table): void {
            $table->id();
            $table->string('partner');
            $table->unsignedBigInteger('partner_user_id');
            $table->string('email')->nullable();
            $table->string('display_name')->nullable();
            $table->string('role')->nullable();
            $table->string('nonce');
            $table->timestamp('expires_at');
            $table->string('redirect_back_url');
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->json('payload')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('wallet_webhook_events', function (Blueprint $table): void {
            $table->id();
            $table->string('partner');
            $table->foreignId('wallet_transaction_id')
                ->constrained('wallet_transactions')
                ->cascadeOnDelete();
            $table->string('event');
            $table->string('url');
            $table->boolean('success')->default(false);
            $table->unsignedInteger('status_code')->nullable();
            $table->text('error')->nullable();
            $table->json('payload')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallet_webhook_events');
        Schema::dropIfExists('wallet_sso_events');
    }
};


