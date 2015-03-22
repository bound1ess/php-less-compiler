PHPUNIT="vendor/bin/phpunit"
PORT=8000

server:;   php -S localhost:$(PORT) -t coverage-report
coverage:; $(PHPUNIT) --coverage-html coverage-report
