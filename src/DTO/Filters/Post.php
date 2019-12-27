<?php

declare(strict_types=1);

namespace App\DTO\Filters;

use App\Entity\Post as PostEntity;

class Post implements FilterDto
{
    private $userId;
    private $status;
    private $dateFrom;
    private $dateTo;

    protected $fields = ['dateFrom', 'dateTo', 'userId', 'status'];

    public function getData(): array
    {
        $arr = [];
        foreach ($this->fields as $field) {
            if (! is_null($this->$field)) {
                $arr[$field] = $this->$field;
            }
        }
        return $arr;
    }

    public function set(?string $userId, ?string $status, ?string $dateFrom, ?string $dateTo): void
    {
        $this->userId = $userId;
        $this->status = in_array($status, PostEntity::STATUSES) ? (int) $status : null;
        $this->dateFrom = $dateFrom ?: null;
        $this->dateTo = $dateTo ?: null;
    }

    public function getUserId(): ?string
    {
        return $this->userId;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function getDateFrom(): ?string
    {
        return $this->dateFrom;
    }

    public function getDateTo(): ?string
    {
        return $this->dateTo;
    }
}
