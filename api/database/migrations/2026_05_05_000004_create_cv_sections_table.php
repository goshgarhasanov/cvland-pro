<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cv_sections', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->foreignUlid('cv_id')->constrained()->cascadeOnDelete();
            $table->string('type');
            $table->string('title')->nullable();
            $table->json('content')->nullable();
            $table->unsignedInteger('position')->default(0);
            $table->timestamps();

            $table->index(['cv_id', 'position']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cv_sections');
    }
};
