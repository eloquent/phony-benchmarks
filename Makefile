.PHONY: benchmarks
benchmarks: install
	bin/run

.PHONY: install
install:
	composer install
