USERID=$(shell id -u)
GROUPID=$(shell id -g)

# Docker variables
FIG=docker-compose
HAS_DOCKER:=$(shell command -v $(FIG) 2> /dev/null)
ifdef HAS_DOCKER
	EXEC=$(FIG) exec -u $(USERID):$(GROUPID) app
	RUN=$(FIG) run -u $(USERID):$(GROUPID) --rm app
	RUN_ASSETS=$(FIG) run -u $(USERID):$(GROUPID) --rm assets
	EXEC_ASSETS=$(FIG) exec -u $(USERID):$(GROUPID) --rm assets
	RUN_DB=$(FIG) run -u $(USERID):$(GROUPID) --rm db --max-allowed-packet=6710886400
else
	EXEC=
	RUN=
	RUN_ASSETS=
	EXEC_ASSETS=
	RUN_DB=
endif

# Symfony command
CONSOLE=php bin/console
ENV=dev

# SQL dump file (for setup only)
DUMP_NAME=notibook.sql
DUMP_PATH=..

# Source pour la documentation du Makefile : http://marmelab.com/blog/2016/02/29/auto-documented-makefile.html
.DEFAULT_GOAL := help

#================================================================#
#                                                                #
#                     Commandes principales                      #
#                                                                #
#================================================================#

setup: docker.up.daemon packages.update db.create db.import db.migrations.migrate build ansible.roles ## Initialise Docker, installe les hooks et install le projet. Nécessite un dump de la base (voir README.md)
.PHONY: setup

update: packages.update db.migrations.migrate build ## Met à jour le projet, sa base de données et ses assets (ne modifie pas les versions des librairies)
.PHONY: update

upgrade: packages.upgrade db.migrations.migrate build ## Met à jour le projet, sa base de données, ses assets ET les versions des librairies (front et back)
.PHONY: upgrade

build: ## Met à jour les assets. Supporte le paramètre env (dev ou prod)
	$(EXEC) $(CONSOLE) assets:install --symlink --env=$(ENV)
ifeq ($(ENV),dev)
	$(RUN_ASSETS) npm run dev
else
	$(RUN_ASSETS) npm run build
endif
.PHONY: build

lint: ## Vérifie les coding standards des différents fichiers du projet (sass, js, twig, php, yaml)
	$(EXEC) php-cs-fixer fix src/ --dry-run --diff
	$(EXEC) $(CONSOLE) lint:twig templates --env=$(ENV)
	$(EXEC) $(CONSOLE) lint:yaml config --env=$(ENV)
	$(EXEC) $(CONSOLE) lint:yaml translations --env=$(ENV)
	$(RUN_ASSETS) npm run lint-sass
	$(RUN_ASSETS) npm run lint-js
.PHONY: lint

clean: assets.clean build ## clean tous les assets et les build à nouveau
.PHONY: clean

sitemap: ## Construit les fichiers de sitemap
	$(EXEC) $(CONSOLE) presta:sitemap:dump --gzip --env=$(ENV)
.PHONY: sitemap

csfix: ## Corrige les problèmes de coding standards
	$(EXEC) php-cs-fixer fix src/
.PHONY: csfix

analysis: ## Analyse statique du code, pour détecter les problèmes potentiels
	$(EXEC) vendor/bin/phpstan analyse
.PHONY: analysis

help:
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | sort | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-9s\033[0m %s\n", $$1, $$2}'
.PHONY: help

start: ## Démarre les containers Docker et build les assets en mode production
	$(FIG) up --remove-orphans
.PHONY: start

run: build ## Lance la compilation via Webpack et surveille la modification des fichiers (mode dev)
	$(RUN_ASSETS) npm run watch
.PHONY: run

stop:           ## Arrête les containers Docker
	$(FIG) down
.PHONY: stop

destroy:        ## Détruit les containers et volumes associés
	$(FIG) down -v
.PHONY: destroy

logs:           ## Affiche les logs des containers db/nginx/php-fpm/assets
	$(FIG) logs -f db nginx app assets
.PHONY: logs

logs.assets:    ## Affiche les logs du container assets (Webpack / nodejs)
	$(FIG) logs -f assets
.PHONY: logs.assets

console:        ## Lance la ligne de commande dans le container app
	$(EXEC) /bin/bash
.PHONY: console

console.assets: ## Lance la ligne de commande dans le container assets
	$(RUN_ASSETS) /bin/bash
.PHONY: console.assets

#================================================================#
#                                                                #
#                         Sous-commandes                         #
#                                                                #
#================================================================#

#
# Met à jour les package managers et install les composants (front et back)
#
packages.update: packages.back.install packages.front.install
.PHONY: packages.update

#
# Met à jour les package managers et met à jour les composants (front et back)
#
packages.upgrade:
	$(EXEC) composer update --prefer-dist --no-progress --no-suggest --no-interaction
	$(RUN_ASSETS) npm upgrade
.PHONY: packages.upgrade

#
# Met à jour les composants pour php / code serveur
#
packages.back.install:
	$(EXEC) composer install --prefer-dist --no-progress --no-suggest --no-interaction

#
# Initialise les composants pour le front (js / sass / css)
#
packages.front.install:
	$(RUN_ASSETS) npm install --no-audit
.PHONY: packages.front.install

#
# Supprime les fichiers temporaires, en vue d'être reconstruits (front et back)
#
assets.clean:
	$(EXEC) rm -rf public/media/cache/*
	$(EXEC) $(CONSOLE) cache:clear --env=$(ENV)
	$(RUN_ASSETS) npm run clean
.PHONY: assets.clean

#
# Initialise la base de données
#
db.init: db.create db.migrations.migrate
.PHONY: db.init

#
# Met à jour de manière forcée le schema de base de données (à éviter)
#
db.schema.update:
	$(EXEC) $(CONSOLE) doctrine:schema:update --force --env=$(ENV)
.PHONY: db.schema.update

#
# Lance les migrations (met à jour la base de données)
#
db.migrations.migrate:
	$(EXEC) $(CONSOLE) doctrine:migrations:migrate --allow-no-migration -n --env=$(ENV)
.PHONY: db.migrations.migrate

#
# Génère un fichier de migration contenant les différences
# de schéma avec la base précédente
#
db.migrations.diff:
	$(EXEC) $(CONSOLE) doctrine:migrations:diff --env=$(ENV) -n
.PHONY: db.migrations.diff

#
# Crée la base de données et les tables
#
db.create:
	$(EXEC) $(CONSOLE) doctrine:database:create --if-not-exists --env=$(ENV)
.PHONY: db.create

#
# Supprime la base de données
#
db.drop:
	$(EXEC) $(CONSOLE) doctrine:schema:drop --force --env=$(ENV)
	$(EXEC) $(CONSOLE) doctrine:database:drop --force --env=$(ENV)
.PHONY: db.drop

#
# Importe la base de données depuis un dump
# (rangé dans le dossier parent, par défaut)
#
db.import:
ifneq ($(DUMP_PATH), .)
	cp $(DUMP_PATH)/$(DUMP_NAME) .
endif
	$(EXEC) $(CONSOLE) doctrine:database:import $(DUMP_NAME)
	rm $(DUMP_NAME)
.PHONY: db.import

#
# Déploie sur le serveur de demo
#
deploy.demo:
	ansible-playbook -i .ansible/demo .ansible/deploy/deploy.yml
.PHONY: deploy.demo

#
# Déploie sur le serveur de prod
#
deploy.prod:
	ansible-playbook -i .ansible/prod .ansible/deploy/deploy.yml
.PHONY: deploy.prod

#
# Installe les rôles ansible du projet
#
ansible.roles:
	ansible-galaxy install cbrunnkvist.ansistrano-symfony-deploy -p .ansible/roles --ignore-errors

docker.up.daemon:
	$(FIG) up -d --remove-orphans --build
