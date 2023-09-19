.PHONY: build run php test fix stop restart analyse

build:
	./vendor/bin/sail build --no-cache

run:
	./vendor/bin/sail up -d

php:
	./vendor/bin/sail shell

test:
	./vendor/bin/sail test

coverage:
	./vendor/bin/sail test --coverage-html reports/

fix:
	./vendor/bin/sail composer csf

stop:
	./vendor/bin/sail stop

restart: stop run
