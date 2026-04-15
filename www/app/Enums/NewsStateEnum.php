<?php

namespace App\Enums;

enum NewsStateEnum: string
{
    case DRAFT = 'draft';
    case IN_REVIEW = 'in_review';
    case SCHEDULED = 'scheduled';
    case PUBLISHED = 'published';
    case ARCHIVED = 'archived';

    public function label(): string
    {
        return match($this) {
            self::DRAFT => 'Rascunho',
            self::IN_REVIEW => 'Em Revisão',
            self::SCHEDULED => 'Agendado',
            self::PUBLISHED => 'Publicado',
            self::ARCHIVED => 'Arquivado',
        };
    }
}
