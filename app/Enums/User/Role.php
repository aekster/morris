<?php

declare(strict_types=1);

namespace Morris\Core\Enums\User;

use Morris\Core\Traits\Enum\HasLabels;

enum Role: string
{
    use HasLabels;
    case SuperAdministrator = "super_administrator";
    case Administrator = "administrator";
    case EventOrganizer = "event_organizer";
    case Coordinator = "coordinator";
    case Host = "host";
    case Attendee = "attendee";

    public static function getElectiveRoles(): array
    {
        return [self::Host, self::Attendee];
    }
}
