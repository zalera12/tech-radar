.PHONY: up down build up-dev down-dev

build:
	docker build -t radar_app .
up:
	docker compose -f compose.yaml up -d
up-dev:
	docker compose -f compose.dev.yaml up -d
down:
	docker compose -f compose.yaml down
down-dev:
	docker compose -f compose.dev.yaml down
