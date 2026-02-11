<?php // app/Enums/DataSource.php

namespace App\Enums;

/**
 * Enum representing different data sources.
 */

enum DataSource: string
{
    case ORIGINAL = 'original';
    case USER = 'user_generated';
    case AI = 'ai_generated';
}
