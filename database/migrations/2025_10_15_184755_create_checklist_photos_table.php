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
        Schema::create('checklist_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('checklist_item_id')->constrained()->onDelete('cascade');
            $table->string('file_path');
            $table->timestamp('timestamp');
            $table->string('gps_coordinates')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checklist_photos');
    }
};
