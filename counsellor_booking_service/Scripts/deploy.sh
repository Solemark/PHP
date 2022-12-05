set -e

export HOME=/home/mattgardiner

rm -rf /home/mattgardiner/prod
git clone https://www.github.com/MattGardiner97/7DayBooking.git /home/mattgardiner/prod
composer install -d /home/mattgardiner/prod/Website --no-interaction
cp /home/mattgardiner/.env.prod /home/mattgardiner/prod/Website/.env
php /home/mattgardiner/prod/Website/artisan key:generate

chmod -R 777 /home/mattgardiner/prod/Website/storage

php /home/mattgardiner/prod/Website/artisan migrate
php /home/mattgardiner/prod/Website/artisan db:seed
