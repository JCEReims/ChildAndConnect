# ChildAndConnect

Child and Connect is a trademark of JCE Reims (http://www.jcereims.fr).

The technical creator is Jimmy Patat (contact@imagim.com).

## Requiements

You should install the last version of php composer and mysql 5.1 or higher.

## How to install

Clone the repository with command line

git clone https://github.com/JCEReims/ChildAndConnect.git

Run php composer

composer install

In mysql, create the database ccapplicqesoft and create an associate user_id

Use the script sql/database.sql to create the structure.

Open files app/config/config_dev.yml and app/config/config_dev.yml and set right database parameters.

Create the admin user

php app/console fos:user:create adminuser --super-admin
php app/console fos:user:activate adminuser

php app/console server:run
admin_jce
