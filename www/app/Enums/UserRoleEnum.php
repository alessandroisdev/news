<?php

namespace App\Enums;

enum UserRoleEnum: string
{
    case ADMIN = 'admin';
    case MANAGER = 'manager';
    case COLUMNIST = 'columnist';
    case SUBSCRIBER = 'subscriber';

    public function label(): string
    {
        return match($this) {
            self::ADMIN => 'Administrador',
            self::MANAGER => 'Gestor',
            self::COLUMNIST => 'Colunista',
            self::SUBSCRIBER => 'Assinante',
        };
    }
}
