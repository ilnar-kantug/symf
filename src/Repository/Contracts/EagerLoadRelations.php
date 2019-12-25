<?php

declare(strict_types=1);

namespace App\Repository\Contracts;

use Doctrine\ORM\QueryBuilder;

interface EagerLoadRelations
{
    public function getRelationsNames(): array;

    public function fetchRelations(array $relations, string $alias, QueryBuilder $query): void;
}
