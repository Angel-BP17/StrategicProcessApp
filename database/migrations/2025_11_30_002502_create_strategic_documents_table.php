<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('strategic_documents', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('path');
            $table->foreignId('file_id')->constrained('media_files')->cascadeOnDelete();
            $table->string('type')->nullable()->index();
            $table->string('category')->nullable()->index();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('strategic_documents');
    }
};
