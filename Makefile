test: test-8.3 test-8.4

coverage-badge:
	docker build --file dockerfile.testing . --build-arg PHP_VERSION=8.3 -t apie-testing-8.3-latest
	docker run --rm -e PHP_VERSION=8.3 -e DEPENDENCIES=latest -v .:/app -w /app apie-testing-8.3-latest composer update
	docker run --rm -e PHP_VERSION=8.3 -e DEPENDENCIES=latest -v .:/app -w /app apie-testing-8.3-latest php -d memory_limit=-1 vendor/bin/phpcov merge --html projectCoverage --clover coverage.xml ./coverage
	docker run --rm -e PHP_VERSION=8.3 -e DEPENDENCIES=latest -v .:/app -w /app apie-testing-8.3-latest bin/create-coverage-badges

test-8.4:
	docker build --file dockerfile.testing . --build-arg PHP_VERSION=8.4 -t apie-testing-8.4-latest
	docker run --rm -e PHP_VERSION=8.4 -e DEPENDENCIES=latest -v .:/app -w /app apie-testing-8.4-latest bin/run-tests coverage/$(PHP_VERSION)_$(DEPENDENCIES).cov $(DEPENDENCIES)

test-8.3:
	docker build --file dockerfile.testing . --build-arg PHP_VERSION=8.3 -t apie-testing-8.3-latest
	docker run --rm -e PHP_VERSION=8.3 -e DEPENDENCIES=latest -v .:/app -w /app apie-testing-8.3-latest bin/run-tests coverage/$(PHP_VERSION)_$(DEPENDENCIES).cov $(DEPENDENCIES)