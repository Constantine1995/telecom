<?php

namespace App\Models;

use App\Enums\Gender;
use App\Enums\PropertyType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;

class Passport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'last_name',
        'first_name',
        'middle_name',
        'gender_type',
        'birth_date',
        'issue_date',
        'issue_by_organization',
        'issue_by_number',
        'birthplace',
        'serial_number',
        'main_photo',
        'registration_photo',
        'registration_date',
        'address_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::updating(function ($passport) {
            // Удаляем старые изображения, если поле изменилось
            self::deleteOldImages($passport, ['main_photo', 'registration_photo']);
        });

        static::deleting(function ($passport) {
            // Удаляем все изображения, связанные с моделью, перед ее удалением
            self::deleteImages($passport, ['main_photo', 'registration_photo']);
        });
    }

    protected static function deleteOldImages($passport, array $fields)
    {
        foreach ($fields as $field) {
            // Проверяем, изменилось ли поле и было ли у него предыдущее значение
            if ($passport->isDirty($field) && !is_null($passport->getOriginal($field))) {
                self::deleteImage($passport->getOriginal($field));
            }
        }
    }

    protected static function deleteImages($passport, array $fields)
    {
        foreach ($fields as $field) {
            // Проверяем, есть ли значение у поля
            if (!empty($passport->$field)) {
                self::deleteImage($passport->$field);
            }
        }
    }

    protected static function deleteImage($path)
    {
        // Проверяем, существует ли файл в хранилище перед удалением
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function fullName(): string
    {
        return $this->last_name . ' ' . $this->first_name . ' ' . $this->middle_name;
    }


    protected function formattedIssueDate(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->issue_date ? Carbon::parse($this->issue_date)->format('d-m-Y') : null
        );
    }


    protected function formattedBirthDate(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->birth_date ? Carbon::parse($this->birth_date)->format('d-m-Y') : null
        );
    }

    public function casts(): array
    {
        return [
            'birth_date' => 'date',
            'issue_date' => 'date',
            'registration_date' => 'date',
            'gender_type' => Gender::class,
            'property_type' => PropertyType::class,
        ];
    }

}
