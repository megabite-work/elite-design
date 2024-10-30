include docker/.env

DC = docker compose -f ./docker/docker-compose-$(TARGET).yml
PHP = $(DC) exec -u www-data php
NGINX = $(DC) exec -it nginx
DB = $(DC) exec -it db
CI = composer install --prefer-dist --no-progress --no-scripts --no-interaction
CIP = composer install --no-progress --no-scripts --no-interaction --no-dev --optimize-autoloader
BIN = php artisan
DMM = $(BIN) migrate --force
CC = $(BIN) optimize:clear

##################
# Docker compose
##################

dc_build:
	@$(DC) build

dc_start:
	@$(DC) start

dc_stop:
	@$(DC) stop

dc_up:
	@$(DC) up -d --remove-orphans

dc_ps:
	@$(DC) ps

dc_logs:
	@$(DC) logs -f

dc_down:
	@$(DC) down --rmi=local --remove-orphans



##################
# App
##################

php_refresh:
	@$(PHP) $(CIP)
	@$(PHP) $(DMM)
	@$(PHP) $(CC)

php_bash:
	@$(PHP) bash

nginx_bash:
	@$(NGINX) bash

db_bash:
	@$(DB) bash

php_ci:
	@$(PHP) $(CI)

composer: ## Run composer, pass the parameter "c=" to run a given command, example: make composer c='req symfony/orm-pack'
	@$(eval c ?=)
	@$(PHP) composer $(c)

bc: ## Run bin/console, pass the parameter "c=" to run a given command, example: make bc c='make:entity'
	@$(eval c ?=)
	@$(PHP) $(BIN) $(c)

restart:
	@$(DC) down --rmi=local --remove-orphans
	@$(DC) up -d --build --remove-orphans
	@$(PHP) $(CC)
	@$(PHP) composer install
	@$(PHP) bash

prod:
	@$(DC) up -d --build --remove-orphans
	@$(PHP) $(CIP)
	@$(PHP) $(CC)