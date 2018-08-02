# Rapd
Super KISS PHP web app library.

## Quick start
```sh
mkdir myproject
cd myproject
echo "{}" >> composer.json
composer require asmundstavdahl/rapd:dev-master
cp -r vendor/asmundstavdahl/rapd/skeleton/* ./
php -S localhost:8080 --docroot=public/ &
sleep 1
firefox localhost:8080
```
