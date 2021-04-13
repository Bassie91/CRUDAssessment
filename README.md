<p align="center">PHP Assessment</p>

## Task

1. Setup a MySQL database

Setup a MySQL db with a table called “users” which has the following fields:
- Firstname
- Surname
- DateOfBirth
- PhoneNumber
- Email

2. API Setup

- The API should have endpoints that allow users to be created, fetched, updated and deleted from the
database.
- The API should be secured using an API Key.
- The endpoints should return JSON, the response should indicate if the query has been successful, along
with any appropriate data.

3. Front-end setup

Please setup a very simple front end that will interact with this API. (Output only needs to be displayed in
the console)

## Install

Set up database in .env file.

Then in terminal: 
Install laravel and its dependecies.
````
 composer install
````
Instal front end dependencies.
````
 npm install
````
Create database and tables.
````
 php artisan migrate
````
Populate databases.
````
 php artisan db:seed --class=SuperUserSeeder
````
````
 php artisan db:seed --class=UserSeeder
````
Run application.
````
 php artisan serve
````
To run tests.
````
 php vendor/bin/phpunit tests/Feature
````

## Created/edited files

Backend
- routes\api.php
- app\database\migrations\2014_10_12_000000_create_users_table.php
- app\database\migrations\2014_04_09_000000_create_superusers_table.php
- app\database\seeders\UserSeeder.php
- app\database\seeders\SuperUserSeeder.php
- app\models\User.php
- app\models\SuperUser.php
- app\Http\controllers\UserController.php
- app\Http\controllers\AuthController.php
- app\Http\Kernel.php
- tests\Feature\UsersTest.php

Frontend
- views\welcome.blade.php
- resources\js\app.js
- resources\js\bootstrap.js
- resources\js\components\Users.vue
- resources\js\components\Navbar.vue



