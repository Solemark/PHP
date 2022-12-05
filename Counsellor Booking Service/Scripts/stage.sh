set -e

export HOME=/home/mattgardiner

composer install -d /home/mattgardiner/staging/Website --no-interaction
cp /home/mattgardiner/.env.stage /home/mattgardiner/staging/Website/.env
php /home/mattgardiner/staging/Website/artisan key:generate

chmod -R 777 /home/mattgardiner/staging/Website/storage

php /home/mattgardiner/staging/Website/artisan migrate
php /home/mattgardiner/staging/Website/artisan db:seed
