.PHONY: all build run composer test check

all: build

build: build-prepare build-backend build-frontend apply-migrations

build-prepare:
	test -f .env || cp .env.example .env

build-backend:
	docker-compose run --rm php-fpm bash -c 'composer install --prefer-dist'

build-frontend:
	docker-compose run --rm yarn bash -c 'yarn install && yarn run encore production'

apply-migrations:
	docker-compose run --rm php-fpm bash -c 'bin/console doctrine:migrations:migrate -n'

run:
	docker-compose up

test:
	docker-compose run --rm php-fpm bash -c 'sleep 5; php bin/console doctrine:m:m -n --env test && php bin/console doctrine:fixtures:load --append -n --env test && php bin/console rigpick:mat-view --env=test && ./vendor/bin/codecept run'

composer: ## Run this command to start temporary container with php and use composer and other PHP tools.
	docker-compose run --rm php-fpm bash

yarn: ## Run this command to start temporary container with php and use composer and other PHP tools.
	docker-compose run --rm yarn bash

# Checks of Continuous Integration
check: check-composer-validity check-composer-security check-migrations check-yaml

check-composer-validity:
	docker-compose run --rm php-fpm bash -c 'composer validate --no-check-all --strict'

check-composer-security:
	docker-compose run --rm php-fpm bash -c 'php vendor/bin/security-checker security:check'

check-migrations:
	docker-compose run --rm php-fpm bash -c 'php bin/console doctrine:schema:validate -e prod'

check-yaml:
	docker-compose run --rm php-fpm bash -c 'php bin/console lint:yaml config'
