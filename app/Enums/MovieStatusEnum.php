<?php

namespace App\Enums;

enum MovieStatusEnum: string
{
    case UPCOMING = 'upcoming';
    case ACTIVE = 'active';
    case ARCHIVED = 'archived';
}
