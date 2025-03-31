<?php

use App\Enums\PropertyType;
use App\Models\City;
use App\Models\Region;
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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Region::class)->constrained();
            $table->foreignIdFor(City::class)->constrained();
            $table->enum('property_type', [PropertyType::HOUSE->value, PropertyType::APARTMENT->value])->default(PropertyType::APARTMENT->value); // Тип жилья: квартира или дом
            $table->string('street', 100);
            $table->string('house_number', 20);
            $table->string('apartment_number', 20)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
