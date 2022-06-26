# Steps to create Ecommerce project in Symfony 
------
## TABLE OF CONTENT:
1. [Initialize the project](#introduction)
2. [Make View](#view)
2. [Make Controller](#controller)
4. [Make Entity](#entity)

--------
## 1. Initialize the project: <a name="introduction"></a>
```
composer create-project symfony/skeleton my-project
```
## 2. Install Twig template engine: <a name="view"></a>
```
composer require twig
```
![twig on IDEs.](https://notejoy.s3.amazonaws.com/note_images/2216881.1.Image%202022-06-23%20at%2009.03.24%20undefined.png)
## 3. Create controller:  <a name="controller"></a>
Install library to create controller:
```
composer require --dev symfony/maker-bundle
```
Install Doctrine Annotations to implement custom function annotations for classes and PHP functions.
```
composer require doctrine/annotations
```
Make controller:
```
php bin/console make:controller
```
![Result of make controller](https://notejoy.s3.amazonaws.com/note_images/2216881.1.Image%202022-06-23%20at%2009.10.17%20undefined.png)
## 4. Make entity <a name="entity"></a>
Symfony provides tools for use with databases thanks to Doctrine. These tools support relational databases like MySQL and PostgreSQL and also NoSQL databases like MongoDB.
```
 composer require symfony/orm-pack
```
Modify the DATABASE_URL variable to connect to the database:
```
DATABASE_URL="mysql://root:@127.0.0.1:3306/ecommercial_db?serverVersion=mariadb-10.4.24"
```
Create database:
```
php bin/console make:entity 
```
And next, we execute the command to create the migration file, this file will create a instruction file containing the SQL query.
```
php bin/console make:migration
```
To create tables and databases in PHPMyAdmin, we have to run the following command:
```
php bin/console doctrine:migrations:migrate
```
