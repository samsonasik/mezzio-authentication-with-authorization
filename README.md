# Mezzio 3 with authentication with authorization, featuring crsf, flash, prg

Install
-------

```bash
$ git clone https://github.com/samsonasik/laminas-authentication-with-authorization.git
$ cd laminas-authentication-with-authorization && composer install
$ cp config/autoload/local.php.dist config/autoload/local.php
$ composer development-enable
```

Modify the `config/autoload/local.php` as your local dev db config.

Configuration
-------------

Configure your `config/autoload/local.php` with your local DB config with username and password field. The password field value must have hashed with bcrypt, if you are using postgresql (assumption using user "developer" and create db named "mezzio"):

```sql
$ createdb -Udeveloper mezzio
Password:

$ psql -Udeveloper mezzio
Password for user developer:

psql (10.1)
Type "help" for help.

expressive=# CREATE TABLE users(username character varying(255) PRIMARY KEY NOT NULL, password text NOT NULL, role character varying(255) NOT NULL DEFAULT 'user');
CREATE TABLE

expressive=# CREATE EXTENSION pgcrypto;
CREATE EXTENSION

expressive=# INSERT INTO users(username, password, role) VALUES('samsonasik', crypt('123456', gen_salt('bf')), 'user');
INSERT 0 1

expressive=# INSERT INTO users(username, password, role) VALUES('admin', crypt('123456', gen_salt('bf')), 'admin');
INSERT 0 1
```

Running
-------

1. Clear browser cache
2. Run the php -S command:

```php
$ php -S localhost:8080 -t public
```

3. Open browser: http://localhost:8080
