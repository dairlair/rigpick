.PHONY: all build

all: build

build:
	docker-compose run --rm php-fpm bash -c 'cd /var/www/html && composer install --prefer-dist'
