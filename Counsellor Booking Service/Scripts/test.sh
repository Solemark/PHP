set -e

php /home/mattgardiner/staging/Website/vendor/bin/phpunit -c /home/mattgardiner/staging/Website/phpunit.xml --coverage-html /home/mattgardiner/githook/coverage --testdox-html /home/mattgardiner/githook/testresults.html --whitelist /home/mattgardiner/staging/Website/app /home/mattgardiner/staging/Website/tests/Unit > /home/mattgardiner/githook/phpunit.log