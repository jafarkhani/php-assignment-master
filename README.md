# SalesChamp PHP assignment

This is the basic structure of the project, feel free to extend it in any way you like.

## Directory structure

```
data/       - base set of data that we used to set up your database
logs/       - log files
public/     - accessible via web-server
spec/       - assignment
src/        - source codes for your assignment
vendor/     - third party libraries
```

You should implement your solution in `/src` directory.

## Database

You should fill in database credentials in `src/settings.php`.

## running the assignment
- First of all please run 'composer update' command to download used packages.
- I just changed the sended project, so you can run it by the PHP built-in server in project by 
    running php -S 0.0.0.0:8080 -t public public/index.php
- I created the database in https://mongodb.com so you don't have to do anything about that.
- In postman set the host variable to http://localhost:8080/api/v1

## more information about project
I'm always used to prepare some core classes at first to increase the development speed. So, the core folder contains some classes which are responsible for some repeated codings.
