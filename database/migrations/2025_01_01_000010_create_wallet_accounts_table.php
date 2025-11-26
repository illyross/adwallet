<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('wallet_accounts', function (Blueprint $table): void {
            $table->id();
            $table->string('partner')->default('escort');
            $table->unsignedBigInteger('partner_user_id');
            $table->string('email')->nullable();
            $table->string('display_name')->nullable();
            $table->string('role')->nullable();
            $table->unsignedBigInteger('balance')->default(0);
            $table->timestamp('last_activity_at')->nullable();
            $table->timestamps();

            $table->unique(['partner', 'partner_user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallet_accounts');
    }
};


