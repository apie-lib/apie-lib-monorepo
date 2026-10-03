test: test-8.4 test-8.5

coverage-badge:
	docker build --file dockerfile.testing . --build-arg PHP_VERSION=8.4 -t apie-testing-8.4-latest
	docker run --rm -e PHP_VERSION=8.4 -e DEPENDENCIES=latest -v .:/app -w /app apie-testing-8.4-latest composer update
	docker run --rm -e PHP_VERSION=8.4 -e DEPENDENCIES=latest -v .:/app -w /app apie-testing-8.4-latest php -d memory_limit=-1 vendor/bin/phpcov merge --html projectCoverage --clover coverage.xml ./coverage
	docker run --rm -e PHP_VERSION=8.4 -e DEPENDENCIES=latest -v .:/app -w /app apie-testing-8.4-latest bin/create-coverage-badges

test-8.5:
	docker build --file dockerfile.testing . --build-arg PHP_VERSION=8.5 -t apie-testing-8.5-latest
	docker run --rm -e PHP_VERSION=8.5 -e DEPENDENCIES=latest -v .:/app -w /app apie-testing-8.5-latest bin/run-tests coverage/8.5_latest.cov

test-8.4:
	docker build --file dockerfile.testing . --build-arg PHP_VERSION=8.4 -t apie-testing-8.4-latest
	docker run --rm -e PHP_VERSION=8.4 -e DEPENDENCIES=latest -v .:/app -w /app apie-testing-8.4-latest bin/run-tests coverage/8.4_latest.cov

test-8.4-lowest:
	docker build --file dockerfile.testing . --build-arg PHP_VERSION=8.4 -t apie-testing-8.4-lowest
	docker run --rm -e PHP_VERSION=8.4 -e DEPENDENCIES=lowest -v .:/app -w /app apie-testing-8.4-lowest bin/run-tests coverage/8.4_lowest.cov --prefer-lowest

test-8.6:
	docker build -t php-master -f resources/dockerfile.php86 .
	docker run -it --rm --name my-running-script -v .:/usr/src/myapp -w /usr/src/myapp  php-master php vendor/bin/phpunit
