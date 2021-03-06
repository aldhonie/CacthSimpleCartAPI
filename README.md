Name: aldhonie
Email: aldhonie@gmail.com

Installation :
1. From terminal install composer: 
```
composer install
```
2. Install `symfony CLI`: https://symfony.com/download
```
curl -sS https://get.symfony.com/cli/installer | bash
```
3. go to file `.env` and save as `.env.local`
rewrite line `29`
```
DATABASE_URL="sqlite:///%kernel.project_dir%/var/app.db"
```
4. Execute create database 
```
php bin/console doctrine:database:create
```
5. Run Migration 
```
php bin/console make:migration              
```
6. Update Schema
```
php bin/console doctrine:schema:update --force
```
7. Run application
```
symfony server:start
```

-----------------------------------------------------------
Postman collection : 
```
https://www.getpostman.com/collections/56a0179ddff20cfc8b62
```
Using Symfony maker bundle to make `Controller` and `entity` 
```
composer require symfony/maker-bundle --dev
```
using symfony `polyfill-uuid` for generate uuid
```
composer require symfony/polyfill-uuid
```