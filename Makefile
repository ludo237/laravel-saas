ROOT_DIR := $(dir $(realpath $(lastword $(MAKEFILE_LIST))))

directories:
	cd $(ROOT_DIR)
	rm -f bootstrap/cache/*.php
	rm -rf storage/logs/* storage/framework/testing/* storage/app/public/*
	chown -R $(shell id -u):$(shell id -g) storage bootstrap/cache
	chmod -R ug+rwX storage bootstrap/cache

sqlite-init:
	cd $(ROOT_DIR)
	rm -rf database/database.sqlite
	touch database/database.sqlite

docker-up: directories sqlite-init
	cd $(ROOT_DIR)
	yes | cp -rf docker/compose.development.yml compose.yml
	yes | cp -rf envs/.env.dev .env
	podman run --rm --tty --interactive --volume $(ROOT_DIR):/app \
		registry.gitlab.com/6go/dx/docker/composer:latest \
		composer install --ignore-platform-reqs
	podman unshare rm -rf $(ROOT_DIR)docker/data/*
	podman compose up -d --force-recreate

docker-down:
	podman unshare rm -rf $(ROOT_DIR)docker/data/*
	podman compose stop
	podman compose down --volumes

docker-laravel-init:
	podman exec -it app make laravel-init

docker-test-init:
	podman exec -it app make test-init

docker-test-coverage:
	podman exec -it app make test-coverage

docker-test:
	podman exec -it app make test

docker-test-fast:
	podman exec -it app make test-fast

docker-audit:
	podman exec -it app make audit

docker-phpstan:
	podman exec -it app make phpstan

docker-pint:
	podman exec -it app make pint

docker-pint-dry:
	podman exec -it app make pint-dry

docker-rector:
	podman exec -it app make rector

docker-composer-update:
	podman run --rm --tty --interactive --volume $(ROOT_DIR):/app \
		registry.gitlab.com/6go/dx/docker/composer:latest \
		composer update -oW --ignore-platform-reqs

docker-ziggy:
	podman exec -it app make ziggy

laravel-init: directories sqlite-init
	cp $(ROOT_DIR)envs/.env.dev .env
	php artisan key:generate
	php artisan migrate:fresh --seed
	php artisan optimize:clear

test-init: directories sqlite-init
	cd $(ROOT_DIR)
	mkdir -p reports/phpunit/coverage
	touch reports/phpunit/coverage/teamcity.txt
	yes | cp -rf envs/.env.dev .env
	php artisan optimize:clear
	php artisan migrate:fresh --env=testing

test-coverage: test-init
	php artisan test \
		--parallel \
		--processes=6 \
		--coverage-html ./reports/phpunit/coverage/html \
		--env=testing

ziggy:
	 php artisan ziggy:generate --types-only

test:
	php artisan test --bail

test-fast:
	php artisan test --parallel --processes=6

audit:
	composer audit

phpstan:
	./vendor/bin/phpstan analyse --error-format gitlab --memory-limit=512M

pint:
	./vendor/bin/pint --parallel

pint-dry:
	./vendor/bin/pint --format=gitlab --test --parallel --bail

rector:
	./vendor/bin/rector --output-format=json

pre-commit:
	bun run prettier
	bun run eslint
	bun run types:check
	make pint
	make phpstan

