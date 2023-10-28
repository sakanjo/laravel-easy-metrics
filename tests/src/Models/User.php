<?php

namespace SaKanjo\EasyMetrics\Tests\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use SaKanjo\EasyMetrics\Tests\Database\Factories\UserFactory;
use SaKanjo\EasyMetrics\Tests\Enums\Gender;

class User extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected static function newFactory()
    {
        return UserFactory::new();
    }

    protected $casts = [
        'gender' => Gender::class,
        'age' => 'integer',
    ];
}
