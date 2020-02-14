# Mezzio 3 with authentication with authorization, featuring crsf, flash, prg

![ci build](https://github.com/samsonasik/mezzio-authentication-with-authorization/workflows/ci%20build/badge.svg)
[![Code Coverage](https://codecov.io/gh/samsonasik/mezzio-authentication-with-authorization/branch/master/graph/badge.svg)](https://codecov.io/gh/samsonasik/mezzio-authentication-with-authorization)

Install
-------

```bash
$ composer create-project samsonasik/mezzio-authentication-with-authorization -sdev
$ cd mezzio-authentication-with-authorization && composer install
$ cp config/autoload/local.php.dist config/autoload/local.php
$ composer development-enable
```

Configuration
-------------

Configure your `config/autoload/local.php` with your local DB config with username and password field. There are examples of `dsn` for both `PostgreSQL` and `MySQL` that you can modify.

For PostgreSQL
--------------

The following commands are example if you are using PostgreSQL (assumption using user "postgres" and create db named "mezzio"), you can create users table with insert username and bcrypt hashed password with pgcrypto extension into users table:

```sql
$ createdb -Upostgres mezzio
Password:

$ psql -Upostgres mezzio
Password for user postgres:

psql (12.1)
Type "help" for help.

mezzio=# CREATE TABLE users(username character varying(255) PRIMARY KEY NOT NULL, password text NOT NULL, role character varying(255) NOT NULL DEFAULT 'user');
CREATE TABLE

mezzio=# CREATE EXTENSION pgcrypto;
CREATE EXTENSION

mezzio=# INSERT INTO users(username, password, role) VALUES('samsonasik', crypt('123456', gen_salt('bf')), 'user');
INSERT 0 1

mezzio=# INSERT INTO users(username, password, role) VALUES('admin', crypt('123456', gen_salt('bf')), 'admin');
INSERT 0 1
```

and you will get the following data:

![user data](https://user-images.githubusercontent.com/459648/73605160-567f0a80-45cd-11ea-9e1d-898df2827758.png)

For MySQL
--------------

The following commands are example if you are using MySQL (assumption using user "root" and create db named "mezzio"), you can create users table with insert username and bcrypt hashed password:

```sql
$ mysql -u root -p -e 'create database mezzio'
Enter password:

$ mysql -u root
Enter password:

mysql> use mezzio
Database changed

mysql> CREATE TABLE users(username varchar(255) PRIMARY KEY NOT NULL, password text NOT NULL, role varchar(255) NOT NULL DEFAULT 'user');
Query OK, 0 rows affected (0.01 sec)

mezzio=# INSERT INTO users(username, password, role) VALUES('samsonasik','$2a$06$Nt2zePoCfApfBGrfZbHZIudIwZpCNqorTjbKNZtPoLCVic8goZDsi', 'user');
Query OK, 1 row affected (0.01 sec)

mezzio=# INSERT INTO users(username, password, role) VALUES('admin', '$2a$06$Y2TtankzyiK/OF1yZA4GsOJBhuoP7o99XbfufEeJ0OOJwjUcPB9LO', 'admin');
Query OK, 1 row affected (0.01 sec)
```

and you will get the following data:

![user data](https://user-images.githubusercontent.com/459648/74274582-e3039880-4d44-11ea-9caa-e8dc8e81a19f.png)

The Authorization Config
------------------------

The authorization configuration saved at `config/autoload/global.php` as ACL:

```php
<?php
// config/autoload/global.php

return [
    // ...
    'mezzio-authorization-acl' => [
        'roles' => [
            'guest' => [],
            'user'  => ['guest'],
            'admin' => ['user'],
        ],
        'resources' => [
            'api.ping',
            'home',
            'admin',
            'login',
            'logout',
        ],
        'allow' => [
            'guest' => [
                'login',
                'api.ping',
            ],
            'user'  => [
                'logout',
                'home',
            ],
            'admin' => [
                'admin',
            ],
        ],
    ],
    // ...
];
```

Running
-------

1. Clear browser cache
2. Run the php -S command:

```php
$ php -S localhost:8080 -t public
```

3. Open browser: http://localhost:8080

4. Login with username : samsonasik, password: 123456 OR username : admin, password : 123456. If you're a logged in user with "user" role, and open `/admin` page, it will show like the following (403 Forbidden), eg: see in [Firefox developer tools](https://developer.mozilla.org/en-US/docs/Tools/Network_Monitor) under "Network" monitor:

![authorized-user-cannot-access-admin-page](https://user-images.githubusercontent.com/459648/73605169-73b3d900-45cd-11ea-9085-3c2bc5e9d966.png)

Test
----

Tests are located under `test` directory, you can run test with composer command:

```bash
$ composer test
```
