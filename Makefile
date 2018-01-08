.PHONY: all build run composer test

all: build

build:
	docker-compose run --rm php-fpm bash -c 'cd /var/www/html && composer install --prefer-dist'
	docker-compose run --rm yarn bash -c 'cd /var/www/html && yarn install && yarn run encore production'

run:
	docker-compose run --rm php-fpm bash -c 'bin/console doctrine:migrations:migrate --no-interaction'
	docker-compose up

test:
	docker-compose run --rm php-fpm bash -c 'sleep 5; php bin/console doctrine:fixtures:load -n --env test && ./vendor/bin/codecept run'

composer: ## Run this command to start temporary container with php and use composer and other PHP tools.
	docker-compose run --rm php-fpm bash

yarn: ## Run this command to start temporary container with php and use composer and other PHP tools.
	docker-compose run --rm yarn bash
