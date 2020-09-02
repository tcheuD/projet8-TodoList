[![SymfonyInsight](https://insight.symfony.com/projects/77537e80-7ff0-4c30-94b6-6b525a687ab5/small.svg)](https://insight.symfony.com/projects/77537e80-7ff0-4c30-94b6-6b525a687ab5)

ToDoList
========

Base du projet #8 : Améliorez un projet existant

https://openclassrooms.com/projects/ameliorer-un-projet-existant-1

# Installation
- clone the repository
- open your console and go to the project directory :
    ```
   composer install
   composer dump-env prod
    ```
- go to .env.local.php
    - Update DATABASE_URL with your environement : 
        ```
        'DATABASE_URL' => 'DATABASE_URL=mysql://root@127.0.0.1:3306/symfony',
        ```
        
### Create the database and load fixtures
- in your console :
   ```
   ./bin/console doctrine:database:create
   ./bin/console make:migration
   ./bin/console doctrine:migrations:migrate
   ./bin/console doctrine:fixtures:load
   ```
  
### Tests:
- Setup the test db
    ```
   ./bin/console doctrine:database:create --env=test
   ./bin/console doctrine:migrations:migrate --env=test
   ./bin/console doctrine:fixtures:load --env=test
    ```
- Run the test with
    ```
    ./bin/phpunit
    ```     
### Start the server in local
 - in your console :
     ```
     ./bin/console server:run
     ```
