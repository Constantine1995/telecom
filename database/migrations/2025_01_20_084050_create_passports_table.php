<?php

use App\Enums\Gender;
use App\Models\Address;
use App\Models\User;
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
        Schema::create('passports', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(User::class)->unique()->constrained()->cascadeOnDelete();
            $table->string('last_name', 50);
            $table->string('first_name', 50);
            $table->string('middle_name', 50);
            $table->enum('gender_type', [Gender::MALE->value, Gender::FEMALE->value]);
            $table->date('birth_date');
            $table->date('issue_date'); // Дата выдачи
            $table->string('issue_by_organization'); // Кем выдан
            $table->string('issue_by_number', 15); // Код подразделения
            $table->string('birthplace'); // Место рождения
            $table->string('serial_number', 100); // Серия и номер

            // Фотографии
            $table->binary('main_photo');
            $table->binary('registration_photo');

            // Место жительства
            $table->date('registration_date'); // Регистрация места жительства
            $table->foreignIdFor(Address::class)->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('passports');
    }
};
