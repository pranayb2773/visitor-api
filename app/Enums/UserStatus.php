<?php

namespace App\Enums;

enum UserStatus: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case PENDING = 'pending';

    public function isActive(): bool
    {
        return $this === UserStatus::ACTIVE;
    }

    public function isInactive(): bool
    {
        return $this === UserStatus::INACTIVE;
    }

    public function isPending(): bool
    {
        return $this === UserStatus::PENDING;
    }

    public static function toArray(): array
    {
        return [
            [
                'id' => UserStatus::ACTIVE,
                'name' => 'Active',
                'summary' => 'It indicates user status in active.'
            ],
            [
                'id' => UserStatus::INACTIVE,
                'name' => 'Inactive',
                'summary' => 'It indicates user status in inactive.'
            ],
            [
                'id' => UserStatus::PENDING,
                'name' => 'Pending',
                'summary' => 'It indicates user status in pending.'
            ],
        ];
    }
}
