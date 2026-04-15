<?php

namespace App\Enums;

enum PaymentStatusEnum: string
{
    case PENDING = 'pending';
    case RECEIVED = 'received';
    case CONFIRMED = 'confirmed';
    case OVERDUE = 'overdue';
    case REFUNDED = 'refunded';

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Aguardando Pagamento',
            self::RECEIVED => 'Recebido (Liquidado)',
            self::CONFIRMED => 'Confirmado',
            self::OVERDUE => 'Vencido',
            self::REFUNDED => 'Reembolsado',
        };
    }
}
