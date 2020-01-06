up: docker-up
down: docker-down
restart: docker-down docker-up
scaffold: composer-install scaffold-db scaffold-node

docker-up:
	docker-compose up -d

docker-down:
	docker-compose down --remove-orphans

docker-up-build:
	docker-compose up -d --build

php:
	docker-compose exec php /bin/bash

composer-install:
	docker-compose run --rm php composer install

scaffold-db:
	docker-compose run --rm php php bin/console doctrine:database:drop --if-exists --force
	docker-compose run --rm php php bin/console doctrine:database:create
	docker-compose run --rm php php bin/console doctrine:migrations:migrate -n
	docker-compose run --rm php php bin/console doctrine:fixtures:load -n

scaffold-node:
	docker-compose run --rm node npm i
	docker-compose run --rm node npm run dev

mysql:
	docker-compose exec mysql /bin/bash

fix-cs:
	docker-compose run --rm php composer cbf
	docker-compose run --rm php composer cs

npm-run-watch:
	docker-compose run --rm node npm run watch

npm-run-dev:
	docker-compose run --rm node npm run dev

run-queue:
	docker-compose run --rm php php bin/console messenger:consume async -vv
