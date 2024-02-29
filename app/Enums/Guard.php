<?php

namespace App\Enums;

enum Guard: string
{
    case API = 'api';
    case WEB = 'web';

    public static function toArray(): array
    {
        return [
            [
                'id' => Guard::API,
                'name' => 'API',
                'summary' => 'Roles and Permissions which will be for API level.'
            ],
            [
                'id' => Guard::WEB,
                'name' => 'web',
                'summary' => 'Roles and Permissions which will be for Web level.'
            ],
        ];
    }
}
