mysql -h localhost -u root bookingsystem < InitialiseDB.sql
cd Website
php artisan migrate
php artisan db:seed