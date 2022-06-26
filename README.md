# Steps to create Ecommerce project in Symfony 
## 1. Initialize the project by, using the command:
```
composer create-project symfony/skeleton my-project
```
## 2. Install Twig template engine:
```
composer require twig
```
![twig on IDEs.](https://notejoy.s3.amazonaws.com/note_images/2216881.1.Image%202022-06-23%20at%2009.03.24%20undefined.png)
## 3. Create controller:
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
