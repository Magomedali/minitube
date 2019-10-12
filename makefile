
down:
	docker-compose down --remove-orphans

pull:
	docker-compose pull

run:
	docker-compose up -d --build

init: down pull run

run-php-cli:
	docker-compose run --rm api-php-cli bash

api-test:
	docker-compose run --rm api-php-cli composer test