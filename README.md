# Child And Connect ©

Child and Connect © is a trademark created by [JCE Reims ©](http://www.jcereims.fr).

The technical creator is [imajim](https://github.com/imajim).

The solution is based on php framework [symfony](https://symfony.com/).

## Requirements

You must install :

- The last version of php composer.
- Php 5.3.3 or higher.
- Mysql 5.1 or higher.

## How to install

- Clone the repository with command line

> git clone https://github.com/JCEReims/ChildAndConnect.git

- Run php composer

> composer install

- In mysql, create the database ccapplicqesoft and create an associate database user.
- Use the script sql/database.sql to create the database structure.
- Open files app/config/config_dev.yml and app/config/config_dev.yml and set right database parameters.
- Create the admin user

> php app/console fos:user:create adminuser --super-admin
php app/console fos:user:activate adminuser

- Run the application

> php app/console server:run

- Connect on http://127.0.0.1:8000 with your favorite browser

Enjoy :D !
