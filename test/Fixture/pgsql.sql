CREATE TABLE IF NOT EXISTS users(
    username character varying(255) PRIMARY KEY NOT NULL,
    password text NOT NULL,
    role character varying(255) NOT NULL DEFAULT 'user'
);

CREATE EXTENSION IF NOT EXISTS pgcrypto;

INSERT INTO users(
    username,
    password,
    role
) VALUES(
    'samsonasik',
    crypt('123456', gen_salt('bf')),
    'user'
) ON CONFLICT(username) DO NOTHING;

INSERT INTO users(
    username,
    password,
    role
) VALUES(
    'admin',
    crypt('123456', gen_salt('bf')),
    'admin'
) ON CONFLICT(username) DO NOTHING;