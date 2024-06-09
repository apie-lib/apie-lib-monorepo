test: test-8.1 test-8.2 test-8.3

coverage-badge:
	docker build --file dockerfile.testing . --build-arg PHP_VERSION=8.3 -t apie-testing-8.3-latest
	docker run --rm -e PHP_VERSION=8.3 -e DEPENDENCIES=latest -v .:/app -w /app apie-testing-8.3-latest composer update
	docker run --rm -e PHP_VERSION=8.3 -e DEPENDENCIES=latest -v .:/app -w /app apie-testing-8.3-latest php -d memory_limit=-1 vendor/bin/phpcov merge --html projectCoverage --clover coverage.xml ./coverage
	docker run --rm -e PHP_VERSION=8.3 -e DEPENDENCIES=latest -v .:/app -w /app apie-testing-8.3-latest bin/create-coverage-badges

test-8.1:
	docker build --file dockerfile.testing . --build-arg PHP_VERSION=8.1 -t apie-testing-8.1-latest
	docker run --rm -e PHP_VERSION=8.1 -e DEPENDENCIES=latest -v .:/app -w /app apie-testing-8.1-latest bin/run-tests coverage/$(PHP_VERSION)_$(DEPENDENCIES).cov $(DEPENDENCIES)

test-8.2:
	docker build --file dockerfile.testing . --build-arg PHP_VERSION=8.2 -t apie-testing-8.2-latest
	docker run --rm -e PHP_VERSION=8.2 -e DEPENDENCIES=latest -v .:/app -w /app apie-testing-8.2-latest bin/run-tests coverage/$(PHP_VERSION)_$(DEPENDENCIES).cov $(DEPENDENCIES)

test-8.3:
	docker build --file dockerfile.testing . --build-arg PHP_VERSION=8.3 -t apie-testing-8.3-latest
	docker run --rm -e PHP_VERSION=8.3 -e DEPENDENCIES=latest -v .:/app -w /app apie-testing-8.3-latest bin/run-tests coverage/$(PHP_VERSION)_$(DEPENDENCIES).cov $(DEPENDENCIES)