
# WEBG301 - Ecommerce Project - GCD0905 - Truong_Linh_Thien
## Authors
![GitHub contributors](https://img.shields.io/github/contributors/ThienDuc1112/GCD0905_WEBG301_Ecommercial_TLT?style=for-the-badge)
- [Nguyen Van Truong](https://www.linkedin.com/in/nv-truong-314641220/)
- [Phan Nhat Linh](https://www.linkedin.com/search/results/all/?keywords=linh%20phan%20nh%E1%BA%ADt&origin=RICH_QUERY_SUGGESTION&position=0&searchId=a8fcb6f8-d1a7-4716-a4c9-93c962e7b558&sid=mcL)
- [Nguyen Duc Thien](https://www.linkedin.com/in/thien-duc-035705228/?fbclid=IwAR1lt1bgns1E-x9zmsSFCSbK5UYoZi8_JHfGSdpX3yUSKn4rn52fE06yCAk)

## Contributing activity
![GitHub commit activity](https://img.shields.io/github/commit-activity/w/ThienDuc1112/GCD0905_WEBG301_Ecommercial_TLT?color=blue&label=Commit%20Activity&style=for-the-badge)
![GitHub last commit](https://img.shields.io/github/last-commit/ThienDuc1112/GCD0905_WEBG301_Ecommercial_TLT?style=for-the-badge)

------
## Table Of Content:
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
