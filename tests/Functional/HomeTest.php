<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Tests\Functional\Concerns\UserHelper;

class HomeTest extends DbWebTestCase
{
    use UserHelper;

    public function testGuest(): void
    {
        $this->client->request('GET', '/');

        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
    }

    public function testAdmin(): void
    {
        $this->client->setServerParameters($this->createAdmin());
        $this->client->request('GET', '/admin');

        $this->assertSame(200, $this->client->getResponse()->getStatusCode());
    }
}
