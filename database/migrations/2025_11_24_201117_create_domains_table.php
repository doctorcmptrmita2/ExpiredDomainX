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
        Schema::create('domains', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('tld')->nullable();
            $table->string('registrar')->nullable();
            $table->string('status'); // expired, expiring, active, pending_delete
            $table->dateTime('registered_at')->nullable();
            $table->dateTime('expires_at')->nullable();
            $table->string('country', 2)->nullable();
            $table->dateTime('last_checked_at')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('expires_at');
            $table->index('tld');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('domains');
    }
};
