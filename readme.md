Symfony Training Project
===

To work with the project you need to have docker on your machine.

To start working with the project fire following commands:

  * ```make up```
  * ```make scaffold```

Then run queue worker:

```make run-queue```

____

Site will be available here:

```http://localhost:8080/```

MailHog here:

```http://localhost:8025/```

Redis web ui here:

root / secret

```http://localhost:9987/```

___
Run tests with:

```make phpunit```

To enter to the project console:

```make php```

There you could check CS:

```composer cs```
