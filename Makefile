# Powered by https://makefiles.dev/

-include .makefiles/Makefile
-include .makefiles/pkg/php/v1/Makefile

.makefiles/%:
	@curl -sfL https://makefiles.dev/v1 | bash /dev/stdin "$@"

#################################################################################

.DEFAULT_GOAL := benchmarks

.PHONY: benchmarks
benchmarks: vendor $(PHP_SOURCE_FILES)
	vendor/bin/phpbench run --report=aggregate benchmarks/FullMockBench.php
	vendor/bin/phpbench run --report=aggregate benchmarks/PartialMockBench.php
	vendor/bin/phpbench run --report=aggregate benchmarks/LargeClassBench.php

.PHONY: ci
ci:: vendor $(PHP_SOURCE_FILES)
	vendor/bin/phpbench run --report=aggregate --progress=none benchmarks/FullMockBench.php
	vendor/bin/phpbench run --report=aggregate --progress=none benchmarks/PartialMockBench.php
	vendor/bin/phpbench run --report=aggregate --progress=none benchmarks/LargeClassBench.php
