ROOT_DIR := $(dir $(realpath $(lastword $(MAKEFILE_LIST))))

# Declare all targets as phony (not file targets)
.PHONY: directories sqlite-init docker-up docker-down docker-laravel-init docker-test-init \
		docker-test-coverage docker-test docker-test-fast docker-audit docker-phpstan \
		docker-pint docker-pint-dry docker-rector docker-composer-update docker-ziggy \
		laravel-init test-init test-coverage ziggy test test-fast audit phpstan pint \
		pint-dry rector pre-commit-fe pre-commit-be pre-commit-all

directories: ## Setup storage directories and permissions
	@cd $(ROOT_DIR); set -e; \
	rm -f bootstrap/cache/*.php; \
	rm -rf storage/logs/* storage/framework/testing/* storage/app/public/*; \
	mkdir -p storage/app/public; \
	chown -R $$(id -u):$$(id -g) storage bootstrap/cache; \
	chmod -R ug+rwX storage bootstrap/cache

sqlite-init: ## Initialize SQLite database
	@cd $(ROOT_DIR); set -e; \
	rm -rf database/database.sqlite; \
	touch database/database.sqlite

docker-up: directories sqlite-init ## Start Docker development environment
	@cd $(ROOT_DIR); set -e; \
	yes | cp -rf docker/compose.development.yml compose.yml; \
	yes | cp -rf envs/.env.dev .env; \
	podman run --rm --tty --interactive --volume $(ROOT_DIR):/app \
		registry.gitlab.com/6go/dx/docker/composer:latest \
		composer install --ignore-platform-reqs; \
	podman unshare rm -rf $(ROOT_DIR)docker/data/*; \
	podman compose up -d --force-recreate

docker-down: ## Stop Docker development environment
	@cd $(ROOT_DIR); set -e; \
	podman unshare rm -rf $(ROOT_DIR)docker/data/*; \
	podman compose stop; \
	podman compose down --volumes

docker-laravel-init: ## Initialize Laravel in Docker container
	@podman exec -it app make laravel-init

docker-test-init: ## Initialize testing environment in Docker
	@podman exec -it app make test-init

docker-test-coverage: ## Run tests with coverage in Docker
	@podman exec -it app make test-coverage

docker-test: ## Run tests in Docker
	@podman exec -it app make test

docker-test-fast: ## Run parallel tests in Docker
	@podman exec -it app make test-fast

docker-audit: ## Run security audit in Docker
	@podman exec -it app make audit

docker-phpstan: ## Run PHPStan analysis in Docker
	@podman exec -it app make phpstan

docker-pint: ## Run Laravel Pint formatter in Docker
	@podman exec -it app make pint

docker-pint-dry: ## Run Pint dry-run in Docker
	@podman exec -it app make pint-dry

docker-rector: ## Run Rector refactoring in Docker
	@podman exec -it app make rector

docker-composer-update: ## Update Composer dependencies in Docker
	@podman run --rm --tty --interactive --volume $(ROOT_DIR):/app \
		registry.gitlab.com/6go/dx/docker/composer:latest \
		composer update -oW --ignore-platform-reqs

docker-ziggy: ## Generate Ziggy routes in Docker
	@podman exec -it app make ziggy

laravel-init: directories sqlite-init ## Initialize Laravel application
	@cd $(ROOT_DIR); set -e; \
	cp $(ROOT_DIR)envs/.env.dev .env; \
	php artisan key:generate; \
	php artisan migrate:fresh --seed; \
	php artisan optimize:clear

test-init: directories sqlite-init ## Initialize testing environment
	@cd $(ROOT_DIR); set -e; \
	mkdir -p reports/phpunit/coverage; \
	touch reports/phpunit/coverage/teamcity.txt; \
	yes | cp -rf envs/.env.dev .env; \
	php artisan optimize:clear; \
	php artisan migrate:fresh --env=testing

test-coverage: test-init ## Run tests with coverage report
	@php artisan test \
		--coverage \
		--log-junit ./reports/junit.xml \
		--coverage-cobertura=./reports/cobertura.xml \
		--parallel \
		--processes=6

ziggy: ## Generate Ziggy TypeScript routes
	@php artisan ziggy:generate --types-only

test: test-init ## Run tests with bail on first failure
	@php artisan test --bail

test-fast: test-init ## Run tests in parallel
	@php artisan test --parallel --processes=6

audit: ## Run Composer security audit
	@composer audit

phpstan: ## Run PHPStan static analysis
	@./vendor/bin/phpstan analyse --error-format gitlab --memory-limit=512M

pint: ## Run Laravel Pint code formatter
	@./vendor/bin/pint --parallel

pint-dry: ## Run Pint dry-run (check only)
	@./vendor/bin/pint --format=gitlab --test --parallel --bail

rector: ## Run Rector automated refactoring
	@./vendor/bin/rector --output-format=json

pre-commit-fe: ## Run frontend pre-commit checks
	@bun run prettier
	@bun run eslint
	@bun run types:check

pre-commit-be: ## Run backend pre-commit checks
	@$(MAKE) pint
	@$(MAKE) phpstan
	@$(MAKE) docker-test-fast

pre-commit-all: ## Run all pre-commit checks
	@$(MAKE) pre-commit-fe
	@$(MAKE) pre-commit-be

