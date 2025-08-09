.SILENT:

DOCKER_COMPOSE = docker-compose
DOCKER_PHP_CONTAINER_EXEC = $(DOCKER_COMPOSE) exec pawns-api
CMD_ARTISAN = $(DOCKER_PHP_EXECUTABLE_CMD) php artisan
FILTER := ""
ifneq ($(strip $(TESTNAME)),)
	FILTER := "--filter=$(TESTNAME)"
endif

start:
	$(DOCKER_COMPOSE) up -d

restart-hard:
	$(DOCKER_COMPOSE) up -d --remove-orphans --build --force-recreate

stop:
	$(DOCKER_COMPOSE) stop

migrate:
	$(DOCKER_PHP_CONTAINER_EXEC) $(CMD_ARTISAN) migrate

reset:
	$(DOCKER_PHP_CONTAINER_EXEC) $(CMD_ARTISAN) migrate:fresh --seed
	$(DOCKER_PHP_CONTAINER_EXEC) $(CMD_ARTISAN) app:new-user developer password
	$(DOCKER_PHP_CONTAINER_EXEC) $(CMD_ARTISAN) api:create-token local
	$(DOCKER_PHP_CONTAINER_EXEC) $(CMD_ARTISAN) db:seed

install:
ifeq (,$(wildcard ./.env))
	cp .env.example .env
	$(DOCKER_PHP_CONTAINER_EXEC) $(CMD_ARTISAN) key:generate
endif
ifeq (,$(wildcard ./vendor/))
	$(DOCKER_PHP_CONTAINER_EXEC) composer install
endif
	$(DOCKER_PHP_CONTAINER_EXEC) $(CMD_ARTISAN) migrate:fresh
	$(DOCKER_PHP_CONTAINER_EXEC) $(CMD_ARTISAN) optimize

exec-php:
	$(DOCKER_PHP_CONTAINER_EXEC) bash

cache:
	$(DOCKER_PHP_CONTAINER_EXEC) $(CMD_ARTISAN) config:clear
	$(DOCKER_PHP_CONTAINER_EXEC) $(CMD_ARTISAN) cache:clear
	$(DOCKER_PHP_CONTAINER_EXEC) $(CMD_ARTISAN) optimize

style:
	echo "- Laravel Pint -"
	$(DOCKER_PHP_CONTAINER_EXEC) ./vendor/bin/pint -v

docs:
	$(DOCKER_PHP_CONTAINER_EXEC) $(CMD_ARTISAN) scramble:export

test:
	$(DOCKER_PHP_CONTAINER_EXEC) $(CMD_ARTISAN) optimize
	$(DOCKER_PHP_CONTAINER_EXEC) $(CMD_ARTISAN) config:clear
	$(DOCKER_PHP_CONTAINER_EXEC) $(CMD_ARTISAN) cache:clear
	$(DOCKER_PHP_CONTAINER_EXEC) $(CMD_ARTISAN) test --stop-on-failure $(FILTER)