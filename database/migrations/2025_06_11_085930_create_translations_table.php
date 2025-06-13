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
        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->string('locale');         // e.g. en, fr, es
            $table->string('key');            // translation key
            $table->text('value');            // actual translated text
            $table->json('tags')->nullable(); // tags for context, e.g. ["mobile", "web"]
            $table->boolean('cdn_ready')->default(false); // bonus point field
            $table->index('locale');
            $table->index('key');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('translations');
    }
};
