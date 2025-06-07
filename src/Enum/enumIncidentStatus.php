<?php

namespace App\Enum;

enum enumIncidentStatus: string
{
    case OPEN = 'open';
    case IN_PROGRESS = 'in_progress';
    case RESOLVED = 'resolved';
    case CLOSED = 'closed';

    public function label(): string
    {
        return match ($this) {
            self::OPEN => 'Ouvert',
            self::IN_PROGRESS => 'En cours',
            self::RESOLVED => 'Résolu',
            self::CLOSED => 'Fermé',
        };
    }
}