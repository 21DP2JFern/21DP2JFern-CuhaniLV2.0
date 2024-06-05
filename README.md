# CuhaniLV 
## Stack
Vue.js, Laravel, MySQL
## Setup

### Laravel backend setup
```
cd laravelBackend    
cp .env.example .env
composer install
php artisan key:generate   
php artisan passport:keys    
php artisan migrate     
php artisan passport:client --personal    
php artisan storage:link              
composer require laravel/telescope --dev     
php artisan telescope:install
    
```
### .env file setup

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE="db_name"
DB_USERNAME="db_user"
DB_PASSWORD=
```
### Vue js frontend setup

```    
cd frontend        
npm install
```

### Run in terminal
```
1. Start frontend

cd frontend    
npm run dev

2. Start Laravel Backend

cd laravelBackend    
php artisan serve
```
