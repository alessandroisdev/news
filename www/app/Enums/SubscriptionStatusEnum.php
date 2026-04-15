<?php

namespace App\Enums;

enum SubscriptionStatusEnum: string
{
    case PENDING = 'pending';
    case ACTIVE = 'active';
    case OVERDUE = 'overdue';
    case CANCELED = 'canceled';

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Pendente',
            self::ACTIVE => 'Ativa',
            self::OVERDUE => 'Em Atraso',
            self::CANCELED => 'Cancelada',
        };
    }
}
