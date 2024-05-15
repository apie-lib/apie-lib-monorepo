test: test-8.1 test-8.2 test-8.3

test-8.1:
	docker build --file dockerfile.php81 . -t apie-php81
	docker run --rm -v $(CURDIR):/app -w /app apie-php81 bin/run-tests coverage/php81

test-8.2:
	docker build --file dockerfile.php82 . -t apie-php82
	docker run --rm -v $(CURDIR):/app -w /app apie-php82 bin/run-tests coverage/php82

test-8.3:
	docker build --file dockerfile.php83 . -t apie-php83
	docker run --rm -v $(CURDIR):/app -w /app apie-php83 bin/run-tests coverage/php83