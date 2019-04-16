# Expressive 3 with auth, crsf, flash, csrf, prg

Install
-------

```bash
$ git clone https://github.com/samsonasik/expressive3-example-auth-with-prg.git
$ cd expressive3-example-auth-with-prg && composer install
$ cp config/autoload/local.php.dist config/autoload/local.php
$ composer development-enable
```

Modify the `config/autoload/local.php` as your local dev db config.

Configuration
-------------

Configure your `config/autoload/local.php` with your local DB config with username and password field. The password field value must have hashed with bcrypt, if you are using postgresql (assumption using user "developer" and create db named "expressive"):

```sql
$ createdb -Udeveloper expressive
Password:

$ psql -Udeveloper expressive
Password for user developer:

psql (10.1)
Type "help" for help.

expressive=# CREATE TABLE users(username character varying(255) PRIMARY KEY NOT NULL, password text NOT NULL);
CREATE TABLE

expressive=# CREATE EXTENSION pgcrypto;
CREATE EXTENSION

expressive=# INSERT INTO users(username, password) VALUES('samsonasik', crypt('123456', gen_salt('bf')));
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
