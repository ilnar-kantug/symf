<?php

declare(strict_types=1);

namespace App\DTO\Filters;

interface FilterDto
{
    public function getDataIfExists(): array;
}
