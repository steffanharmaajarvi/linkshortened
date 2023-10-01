build: up ps composer migration

up:
	docker-compose \
		-f infrastructure/docker-compose.yml \
		-f infrastructure/docker-compose.dev.yml \
		up -d --build --remove-orphans
	docker exec php-fpm mkdir -p /application/var/sessions
	docker exec php-fpm mkdir -p /application/var/log
	docker exec php-fpm chmod -R 777 /application/var/sessions
	docker exec php-fpm chmod -R 777 /application/var/log
	docker exec php-fpm composer recipes:install phpunit/phpunit --force -v


prod:
	rm -rf var/cache/prod
	docker-compose \
		-f infrastructure/docker-compose.yml \
		-f infrastructure/docker-compose.prod.web.yml \
		up -d --build --remove-orphans
	docker exec php-fpm mkdir -p /application/var/sessions
	docker exec php-fpm mkdir -p /application/var/log
	docker exec php-fpm chmod -R 777 /application/var/sessions
	docker exec php-fpm chmod -R 777 /application/var/log
	docker exec -t php-fpm bash -c 'COMPOSER_MEMORY_LIMIT=-1 composer install --no-dev --optimize-autoloader'
	docker exec -t php-fpm bash -c 'bin/console doctrine:migrations:migrate --no-interaction'


ps:
	docker-compose -f infrastructure/docker-compose.yml -f infrastructure/docker-compose.dev.yml ps

composer:
	docker exec -t php-fpm bash -c 'COMPOSER_MEMORY_LIMIT=-1 composer install'

php:
	docker exec -it php-fpm bash

test:
	docker exec -it php-fpm bin/console --env=test doctrine:fixtures:load
	docker exec -it php-fpm vendor/bin/phpunit

migration:
	docker exec -t php-fpm bash -c 'bin/console doctrine:migrations:migrate --no-interaction'
