<?php

/**
 * Class Status
 *
 * @author My Name <my.name@example.com>
 * @internal
 *
 */

namespace App\Model;

class Messages
{
    const MESSAGES_INFO = [
        'EN' => [
            'registered' => 'User is registered in the system!',
            'dontAccess' => 'You dont have permission to access!'
        ],
        'RU' => [
            'registered' => 'Пользователь зарегистрирован в системе!',
            'dontAccess' => 'У вас нет разрешения на доступ!'
        ]
    ];
}
