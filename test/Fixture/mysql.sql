USE mezzio;

CREATE TABLE IF NOT EXISTS users(
    username varchar(255) PRIMARY KEY NOT NULL,
    password text NOT NULL,
    role varchar(255) NOT NULL DEFAULT 'user'
);

INSERT INTO users(
    username,
    password,
    role
) VALUES(
    'samsonasik',
    '$2a$06$Nt2zePoCfApfBGrfZbHZIudIwZpCNqorTjbKNZtPoLCVic8goZDsi',
    'user'
);

INSERT INTO users(
    username,
    password,
    role
) VALUES(
    'admin',
    '$2a$06$Y2TtankzyiK/OF1yZA4GsOJBhuoP7o99XbfufEeJ0OOJwjUcPB9LO',
    'admin'
);