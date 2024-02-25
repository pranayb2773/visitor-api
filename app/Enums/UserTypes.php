<?php

namespace App\Enums;

enum UserTypes: string
{
    case EXTERNAL = 'external';
    case INTERNAL = 'internal';

    public function isInternal(): bool
    {
        return $this === UserTypes::INTERNAL;
    }

    public function isExternal(): bool
    {
        return $this === UserTypes::EXTERNAL;
    }

    public static function toArray(): array
    {
        return [
            [
                'id' => UserTypes::EXTERNAL,
                'name' => 'External',
                'summary' => 'External indicate that user is visitor.'
            ],
            [
                'id' => UserTypes::INTERNAL,
                'name' => 'Internal',
                'summary' => 'Internal indicate that user is belongs to Organisation.'
            ],
        ];
    }
}
