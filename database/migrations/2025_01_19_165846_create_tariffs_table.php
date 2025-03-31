<?php

use App\Enums\PropertyType;
use App\Models\Tag;
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
        Schema::create('tariffs', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->foreignIdFor(Tag::class)->nullable()->constrained()->nullOnDelete();
            $table->decimal('price', 6, 2)->default(0);
            $table->decimal('connection_price', 6, 2)->default(0);
            $table->enum('connection_type', [PropertyType::HOUSE->value, PropertyType::APARTMENT->value])->default(PropertyType::APARTMENT->value);
            $table->integer('speed')->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tariffs');
    }
};
