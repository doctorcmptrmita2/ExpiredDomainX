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
        Schema::create('domain_metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('domain_id')->constrained()->onDelete('cascade');
            $table->integer('organic_traffic')->default(0);
            $table->integer('organic_keywords')->default(0);
            $table->integer('backlinks_total')->default(0);
            $table->integer('referring_domains')->default(0);
            $table->unsignedTinyInteger('ed_score')->default(0); // 0-100
            $table->json('raw_provider_payload')->nullable();
            $table->timestamps();

            $table->index('domain_id');
            $table->index('ed_score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('domain_metrics');
    }
};
