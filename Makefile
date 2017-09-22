.PHONY: benchmarks
benchmarks: install
	vendor/bin/phpbench run benchmarks/FullMockBench.php --report aggregate
	vendor/bin/phpbench run benchmarks/PartialMockBench.php --report aggregate
	vendor/bin/phpbench run benchmarks/LargeClassBench.php --report aggregate

.PHONY: install
install:
	composer install
