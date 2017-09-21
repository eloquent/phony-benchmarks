.PHONY: benchmarks
benchmarks: install
	bin/run

.PHONY: lint
lint: install
	vendor/bin/php-cs-fixer fix

.PHONY: install
install:
	composer install
