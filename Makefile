.PHONY: all build run php

all: build

build:
	docker-compose run --rm php-fpm bash -c 'cd /var/www/html && composer install --prefer-dist'

run:
	docker-compose up

php: ## Run this command to start temporary container with php and use composer and other PHP tools.
	docker-compose run --rm php-fpm bash