.PHONY: up down

git-pull:
	git pull origin main
up:
	docker compose -f compose.yaml up -d
down:
	docker compose -f compose.yaml down
