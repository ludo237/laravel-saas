<?php

declare(strict_types=1);

namespace App\Enums;

enum Queues: string
{
    case DEAFULT = 'default';
    case NOTIFICATIONS = 'notifications';
    case EMAILS = 'emails';
    case LISTENERS = 'listeners';
    case JOBS = 'jobs';
}
