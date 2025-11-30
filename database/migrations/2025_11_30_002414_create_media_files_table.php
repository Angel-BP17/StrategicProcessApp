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
        Schema::create('media_files', function (Blueprint $table) {
            $table->id();

            // Info del proveedor / storage
            $table->string('provider')->default('cloudinary'); // por si luego cambias a otro
            $table->string('disk')->default('cloudinary');     // nombre del disk en config/filesystems.php

            // Identificadores de Cloudinary
            $table->string('public_id')->index();   // ej: facultad/docs/abc123
            $table->string('resource_type')->nullable(); // image, video, raw
            $table->string('format', 10)->nullable();    // pdf, jpg, png, etc.

            // URLs
            $table->string('secure_url');           // URL https principal
            $table->string('thumbnail_url')->nullable(); // si generas thumbnails
            $table->string('folder')->nullable();   // carpeta lógica en Cloudinary

            // Metadatos del archivo
            $table->string('original_name')->nullable(); // nombre original subido
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('bytes')->nullable();
            $table->unsignedInteger('width')->nullable();
            $table->unsignedInteger('height')->nullable();

            // Extras
            $table->json('meta')->nullable();       // tags, transformations, etc.

            // Quién subió el archivo
            $table->foreignId('uploaded_by')->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media_files');
    }
};
