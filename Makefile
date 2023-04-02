test: test-8.1 test-8.2

test-8.1:
	docker build --file dockerfile.php81 . -t apie-php81
	docker run --rm -v $(CURDIR):/app -w /app apie-php81 composer install && vendor/bin/phpunit

test-8.2:
	docker build --file dockerfile.php82 . -t apie-php82
	docker run --rm -v $(CURDIR):/app -w /app apie-php82 composer install && vendor/bin/phpunit