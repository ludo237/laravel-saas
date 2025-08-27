<?php

declare(strict_types=1);

namespace App\Enums;

enum Policies: string
{
    case VIEW = 'view';

    case VIEW_ANY = 'viewAny';

    case CREATE = 'create';

    case UPDATE = 'update';

    case DELETE = 'delete';

    case UPLOAD = 'upload';

    case RELEASE = 'release';

    case REGISTER = 'register';
}
