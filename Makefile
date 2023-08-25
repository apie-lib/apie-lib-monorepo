test: test-8.1 test-8.2

test-8.1:
	docker build --file dockerfile.php81 . -t apie-php81
	docker run --rm -v $(CURDIR):/app -w /app apie-php81 composer install && php -m && php -d pcov.enabled=1 -d pcov.directory=. vendor/bin/phpunit --coverage-clover=coverage/php81.xml --coverage-text --coverage-html=coverage/php81

test-8.2:
	docker build --file dockerfile.php82 . -t apie-php82
	docker run --rm -v $(CURDIR):/app -w /app apie-php82 composer install && php -m && php -d pcov.enabled=1 -d pcov.directory=. vendor/bin/phpunit --coverage-clover=coverage/php82.xml --coverage-text --coverage-html=coverage/php82