<?php

declare(strict_types=1);

namespace App\Filters;

use DateTime;
use Doctrine\ORM\QueryBuilder;

class PostFilter extends Filter
{
    protected function getAvailableFilters(): array
    {
        return ['dateFrom', 'dateTo', 'userId', 'status'];
    }

    protected function dateFrom($dateFrom): QueryBuilder
    {
        return $this->builder->andWhere($this->alias . '.createdAt >= :dateFrom')
            ->setParameter('dateFrom', (DateTime::createFromFormat('d.m.Y', $dateFrom))->setTime(0, 0, 0));
    }

    protected function dateTo($dateTo): QueryBuilder
    {
        return $this->builder->andWhere($this->alias . '.createdAt <= :dateTo')
            ->setParameter('dateTo', (DateTime::createFromFormat('d.m.Y', $dateTo))->setTime(23, 59, 59));
    }

    protected function userId($id): QueryBuilder
    {
        return $this->builder->andWhere($this->alias . '.author = :id')
            ->setParameter('id', $id);
    }

    protected function status($status): QueryBuilder
    {
        return $this->builder->andWhere($this->alias . '.status = :status')
            ->setParameter('status', $status);
    }
}
