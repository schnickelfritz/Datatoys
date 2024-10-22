up:
	composer install --no-scripts
	symfony console importmap:install
	docker compose up -d
	@echo
	@echo "---------------------------------------------"
	@echo " Waiting for MySQL container to be ready ... "
	@echo
	@while ! docker compose exec db mysql -uroot -pgeheim123 -e 'SELECT VERSION()' --silent  >/dev/null 2>&1; do \
		echo ""; \
		echo "... container not ready yet, retry in 2s ... "; \
		sleep 2; \
	done
	@echo "          MySQL container ready!             "
	@echo "---------------------------------------------"
	symfony console doctrine:database:drop --if-exists -n --force
	symfony console doctrine:database:create -n
	symfony console doctrine:migrations:migrate -n
	symfony console doctrine:fixtures:load -n
	symfony console cache:clear
	symfony local:server:start -d
setup:
	composer install
	symfony console importmap:install
down:
	docker compose down
	symfony local:server:stop
stan:
	symfony php vendor/bin/phpstan analyze
cs:
	symfony php vendor/bin/php-cs-fixer fix --allow-risky=yes
fix:
	symfony console doctrine:database:drop --if-exists -n --force
	symfony console doctrine:database:create -n
	symfony console doctrine:migrations:migrate -n
	symfony console doctrine:fixtures:load -n