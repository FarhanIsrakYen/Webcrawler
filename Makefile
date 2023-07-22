DOCKER_COMP = docker-compose

PHP_CONT = $(DOCKER_COMP) exec app

build: 
	@$(DOCKER_COMP) build --pull --no-cache

build: reset
rebuild: reset
reset: down
	@$(DOCKER_COMP) -f docker-compose.yml build
	@$(DOCKER_COMP) -f docker-compose.yml up -d --remove-orphans
	make package
	make migrate
	make down

cache:
	@$(DOCKER_COMP) -f docker-compose.yml exec -T app bash -c \
	"/var/www/bin/console cache:clear"

down: 
	@$(DOCKER_COMP) down --remove-orphans

start: build up

lint: lint-check
lint-check:
	@$(DOCKER_COMP) -f docker-compose.yml exec -T app bash -c "cd /var/www/ && \
	./vendor/bin/phpcs -p"

lint-fix:
	@$(DOCKER_COMP) -f docker-compose.yml exec -T app bash -c "cd /var/www/ && \
	./vendor/bin/phpcbf -p > /dev/null 2>&1 && ./vendor/bin/phpcs -p"

logs: 
	@$(DOCKER_COMP) logs --tail=0 --follow

migrate:
	@$(DOCKER_COMP) -f docker-compose.yml exec -T app bash -c \
	"/var/www/bin/console doctrine:migrations:migrate --no-interaction"

migration:
	@$(DOCKER_COMP) -f docker-compose.yml exec -T app bash -c \
	"/var/www/bin/console make:migration"

package:
	@$(DOCKER_COMP) -f docker-compose.yml exec -T app bash -c "cd /var/www/ && \
	composer install"

sh: 
	@$(PHP_CONT) sh

up: 
	@$(DOCKER_COMP) -f docker-compose.yml up -d --remove-orphans
	make migrate
