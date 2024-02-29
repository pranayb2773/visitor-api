<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Spatie\Permission\Models\Permission as SpatiePermission;

class Permission extends SpatiePermission
{
    protected $fillable = [
        'name',
        'guard_name',
        'description',
    ];

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => str($value)->lower()->replace(' ', '.'),
            set: fn ($value) => str($value)->lower()->replace(' ', '.'),
        );
    }
}
