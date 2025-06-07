<?php

namespace App\Enum;

enum RoleUser: string
{
    case USER = 'ROLE_USER';
    case ADMIN = 'ROLE_ADMIN';
    case EMPLOYEE = 'ROLE_EMPLOYEE';


    public function label(): string
    {
        return match ($this) {
            self::USER => 'Utilisateur',
            self::ADMIN => 'Administrateur',
            self::EMPLOYEE => 'Employé',
        };
    }

}