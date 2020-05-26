Name: aldhonie
Email: aldhonie@gmail.com

Installation :
1. From terminal install composer: 
```
composer install
```
2. go to file `.env` and save as `.env.local`
rewrite line `29`
```
DATABASE_URL="sqlite:///%kernel.project_dir%/var/app.db"
```
3. Execute create database 
```
php bin/console doctrine:database:create
```
4. Run Migration 
```
php bin/console make:migration
```
5. Serve Application 
```
 symfony server:start
```


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

using `Symfony CLI` and `console`
```
php bin/console
symfony
```


