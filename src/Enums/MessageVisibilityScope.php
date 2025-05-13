<?php

namespace App\Enums;

enum MessageVisibilityScope: string
{
    case all = 'all';
    case SameSchool = 'same_school';
    case ContactsOnly = 'contacts_only';
    case Hidden = 'hidden';

    public function label(): string
    {
        return match ($this) {
            self::all => 'Für alle verifizierten Mitglieder',
            self::SameSchool => 'Nur für Mitglieder meiner Schule',
            self::ContactsOnly => 'Nur, wenn ich die Person kenne',
            self::Hidden => 'Nicht auffindbar',
        };
    }
}
