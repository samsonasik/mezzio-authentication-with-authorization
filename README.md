# Mezzio 3 with authentication with authorization, featuring crsf, flash, prg

![ci build](https://github.com/samsonasik/mezzio-authentication-with-authorization/workflows/ci%20build/badge.svg)

Install
-------

```bash
$ git clone git@github.com:samsonasik/mezzio-authentication-with-authorization.git
$ cd mezzio-authentication-with-authorization && composer install
$ cp config/autoload/local.php.dist config/autoload/local.php
$ composer development-enable
```

Configuration
-------------

Configure your `config/autoload/local.php` with your local DB config with username and password field. The password field value must have hashed with bcrypt, if you are using postgresql (assumption using user "developer" and create db named "mezzio"):

```sql
$ createdb -Udeveloper mezzio
Password:

$ psql -Udeveloper mezzio
Password for user developer:

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
