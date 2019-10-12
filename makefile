
down:
	docker-compose down --remove-orphans

pull:
	docker-compose pull

run:
	docker-compose up -d --build

init: down pull run



api-test:
	docker-compose run --rm api-php-cli composer test