<?php

declare(strict_types=1);

namespace App\Contracts;

interface RequestWithDto
{
    public function dto(): DataTransferObject;
}
