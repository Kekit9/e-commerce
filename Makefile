# Determine usefull domains
DOCKER := docker
AWS := aws
LOCALSTACK := localstack

# Commands for use localstack
localstack-up:
	$(LOCALSTACK) start -d

s3-create:
	$(AWS) --endpoint-url=http://localhost:4566 s3 mb s3://catalog-exports

ses-verify-email:
	$(AWS) --endpoint-url=http://localhost:4566 ses verify-email-identity --email-address "hello@example.com"

# Command to initialise localstack
up-s3-email: localstack-up s3-create ses-verify-email

# Command to stop localstack
localstack-down:
	$(LOCALSTACK) stop

# Command for kill localstack
kill-localstack:
	$(DOCKER) kill 'CONTAINER ID'

# Command to stop containers
docker-down:
	$(DOCKER) stop $$($(DOCKER) ps -q)

down: docker-down

# Command for code analyse
phpstan:
	./vendor/bin/phpstan analyse --level=8 --no-progress --memory-limit=2G

php-cs-fixer:
	./vendor/bin/php-cs-fixer fix --dry-run --diff

php-cs-fixer-fix:
	./vendor/bin/php-cs-fixer fix

# Combination to analyse and fix bugs
phpstan-and-fix: phpstan php-cs-fixer-fix
