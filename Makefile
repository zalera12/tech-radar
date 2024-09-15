.PHONY: up down

up:
	docker compose -f compose.yaml up -d
down:
	docker compose -f compose.yaml down
