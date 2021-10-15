# Studi ECF Mediatheque felixBohme

This is a PHP/HTML/JS/CSS coding project for the internal usage of STUDI during the ECF Exams in October 2021. Please read through this document to inform yourself about the project and how to use it.

## Tested Environment
- PHP 7+
- MariaDB 10+
- Windows 10
- Git
- Heroku

## Tools
 - Symfony 5.3
 - composer
 - Doctrine
 - EasyAdminBundle
 - Twitter Bootstrap 5
 - PhpStorm 2021.2.2

## Local Installation
- Install web server depending on your machine
- Setup IDE
- Clone this repo ```gh repo clone felekfasolka/Studi_ECF_Mediatheque_felixBohme ```
- Change ```.env``` file to match your local database
- Run ```composer install``` in your IDE
- Create MySQL or MariaDB database with name ```mediatheque```. 
```php bin/console doctrine:database:create```
- Initiate the database migration of the Entities with  ```php bin/console make:migration```
- Execute the migration with ```php bin/console doctrine:migrations:migrate```
- If you want to fill the database with some demo users, simply run ```php bin/console doctrine:fixtures:load```
- Manually create Employee user(s) with ```ROLE_EDITOR``` (database name ```employee```) to manage the mediatheque and their users (backoffice). See included SQL-script. Without at least one ```employee``` there will be no backend-access. Passwords can be manually hashed with symfony (```php bin/console security:hash-password```)
- run ```php bin/console server:run``` to launch embedded server OR use external services like XAMPP
- Try ```http://127.0.0.1:8000/``` 
- You can sign in and create new User-Accounts (Register).
- IMPORTANT: New Accounts have to be manually enabled by an Employee in the backoffice.
- Login Credential Scheme for predefined User logins (```UserFixtures.php```):```userx@mail.com``` password:```userx``` where ```x``` is a number from ```0 to 24``` (example: ```user5@mail.com | user5``` OR ```user20@mail.com | user20```)
- To access backoffice try to log in with credentials (see Database Demo Import below) ```employee@mail.com | employee``` OR ```boss@mail.com | employee``` for example

## Database Demo Import
- For two demo Employee Accounts run the following SQL Queries:
```
INSERT INTO `employee` VALUES(1, 'employee@mail.com', '[\"ROLE_EDITOR\"]', '$2y$13$DTwFLXYOn6NU5FiajKSvfuDbQa4.4cY5F8Be6NPydVp8fHDQP4L4O');
INSERT INTO `employee` VALUES(2, 'boss@mail.com', '[\"ROLE_EDITOR\"]', '$2y$13$DTwFLXYOn6NU5FiajKSvfuDbQa4.4cY5F8Be6NPydVp8fHDQP4L4O');
```

## Heroku Deployment
- see [Heroku Documentation](https://devcenter.heroku.com/articles/deploying-symfony4) or follow below
- Install Heroku CLI package
- Create free Heroku Account
- Initialize a Git repository and commit the current state

```
git init
git add .
git commit -m "initial import"
```
- create new heroku app
```
heroku create
```
- create Procfile
```
echo 'web: heroku-php-apache2 public/' > Procfile
git add Procfile
git commit -m "Heroku Procfile"
```
- check encoding of Procfile (should be UT8 on Windows machines)
- Configure Symfony to run in the prod environment
```
heroku config:set APP_ENV=prod
```
- Deploy to Heroku
```
git push heroku master
```
- create database structure via doctrine
```
heroku run php bin/console doctrine:schema:update --force
```
- make sure heroku buildpacke and config vars are properly set up
- create and connect to Database (for example ClearDB MySQL)
- Insert demo data or create at least one Employee User
- Open App
```
heroku open
```

## Key features
- 2 different Backends, depending on the User-Role (auto-routing from login-Page)
- Login page (simple static page) - http://127.0.0.1:8000/
- Registration Page
- User Interface for registered and enabled user-accounts
- Employee Backoffice (manage mediatheque items/genres/users)
- Various Pages have different views of the media catalogue (including detail View)
- Various filters and sorting options for views
- Users can ```Reserve``` any available item
- Users can ```Delete``` any Reservation done before
- Users can ```View``` their borrowed items
- Users can ```View``` their due items (3 weeks borrowed or more)
- Employees can ```View/Edit/Delete``` any item in the mediatheque
- Employees can ```Create``` a new media item
- Employees can ```View``` reserved items from all users
- Employees can ```Edit``` reserved items and confirm the pick-up by the user (Issue Desk)
- Employees can ```View``` due items from all users
- Employees can ```Edit``` borrowed items and confirm the return by the user (Return Desk)
- Employees can ```View/Edit/Delete/Create``` Media Genres
- Employees can ```View/Edit/Delete``` User entries (except passwords)
- Search bar for media items
- Image Upload for Employees
- Secured throughs different mechanisms 

## Known issues
- No tests yet
- No User profiles
- User entry completion has to be carried out be an Employee on-site 
- Reservations older than 3 days are not automatically deleted yet. Please find the CLI tool command to count the old Reservations ```php bin/console app:reservation:cleanup```
