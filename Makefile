benchmarks: install
	vendor/bin/athletic -p benchmarks

lint: install
	vendor/bin/php-cs-fixer fix

install: vendor/autoload.php

.PHONY: benchmarks lint install

vendor/autoload.php: composer.lock
	composer install

composer.lock: composer.json
	composer update
